<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\SaleItem;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role === 'super_admin';
        $hasPremiumReport = $isSuperAdmin || ($user->subscription && $user->subscription->package && in_array('laporan_keuangan', $user->subscription->package->features));

        // Soft Paywall: Blokir export jika bukan premium
        if (!$hasPremiumReport && in_array($request->get('action'), ['pdf', 'excel'])) {
            return back()->with('premium_error', 'Fitur Export Laporan (PDF/Excel) khusus untuk pengguna Premium. Silakan Upgrade paket Anda.');
        }

        // Setup Date Range
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        // Soft Paywall: Batasi filter maksimal 31 hari terakhir untuk pengguna Free
        if (!$hasPremiumReport) {
            $limitDate = Carbon::now()->subDays(31)->startOfDay();
            if ($startDate->lt($limitDate)) {
                $startDate = $limitDate->copy();
                session()->flash('premium_warning', 'Pengguna Gratis hanya dapat melihat riwayat laporan maksimal 31 hari terakhir.');
            }
            if ($endDate->lt($limitDate)) {
                $endDate = Carbon::now()->endOfDay();
            }
        }

        // 1. Total Omzet
        $totalOmzet = Sale::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        // 2. Total Pengeluaran (Pembelian)
        $totalPengeluaran = Purchase::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        // 3. Total Transaksi
        $totalTransaksi = Sale::whereBetween('created_at', [$startDate, $endDate])->count();

        $storeId = auth()->user()->store_id;
        $isSuperAdmin = auth()->user()->role === 'super_admin';

        // 4. Estimasi Laba Kotor
        // Laba Kotor = SUM((Harga Jual - HPP) * Qty)
        // Lakukan join antara sales dan sale_items
        $labaKotor = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->when(!$isSuperAdmin, function ($query) use ($storeId) {
                return $query->where('sales.store_id', $storeId);
            })
            ->sum(DB::raw('(sale_items.price - sale_items.purchase_price) * sale_items.quantity'));

        // 5. Chart Data (Omzet Harian)
        // Ambil data omzet dikelompokkan berdasarkan tanggal
        $salesData = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when(!$isSuperAdmin, function ($query) use ($storeId) {
                return $query->where('store_id', $storeId);
            })
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartValues = [];

        // Loop setiap hari dari startDate hingga endDate
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateString = $currentDate->format('Y-m-d');
            $chartLabels[] = $currentDate->format('d M');
            $chartValues[] = isset($salesData[$dateString]) ? $salesData[$dateString]->total : 0;
            
            $currentDate->addDay();
        }

        // 6. Top Selling Products
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_items.product_id', '=', 'products.id')
            ->select(
                DB::raw('COALESCE(products.name, sale_items.custom_name) as name'),
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.subtotal) as total_revenue')
            )
            ->whereBetween('sales.created_at', [$startDate, $endDate])
            ->when(!$isSuperAdmin, function ($query) use ($storeId) {
                return $query->where('sales.store_id', $storeId);
            })
            ->groupBy(DB::raw('COALESCE(products.name, sale_items.custom_name)'))
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        if ($request->action == 'excel') {
            $csvFileName = 'Laporan_Keuangan_' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.csv';
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$csvFileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            
            $callback = function() use($totalOmzet, $labaKotor, $totalPengeluaran, $totalTransaksi, $topProducts) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Ringkasan Keuangan']);
                fputcsv($file, ['Total Omzet', 'Rp ' . number_format($totalOmzet, 0, ',', '')]);
                fputcsv($file, ['Total Laba Kotor', 'Rp ' . number_format($labaKotor, 0, ',', '')]);
                fputcsv($file, ['Total Pengeluaran (Beli)', 'Rp ' . number_format($totalPengeluaran, 0, ',', '')]);
                fputcsv($file, ['Total Transaksi', $totalTransaksi]);
                fputcsv($file, []);
                
                fputcsv($file, ['Top 5 Produk Terlaris']);
                fputcsv($file, ['No', 'Nama Produk', 'Total Qty Terjual', 'Total Pendapatan']);
                foreach ($topProducts as $idx => $prod) {
                    fputcsv($file, [$idx + 1, $prod->name, $prod->total_qty, 'Rp ' . number_format($prod->total_revenue, 0, ',', '')]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        if ($request->action == 'pdf') {
            return view('reports.print', compact(
                'startDate', 'endDate', 
                'totalOmzet', 'totalPengeluaran', 'totalTransaksi', 'labaKotor',
                'topProducts'
            ));
        }

        return view('reports.index', compact(
            'totalOmzet',
            'totalPengeluaran',
            'totalTransaksi',
            'labaKotor',
            'chartLabels',
            'chartValues',
            'topProducts',
            'startDate',
            'endDate',
            'hasPremiumReport'
        ));
    }
}
