<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = date('Y-m-d');

        // Omzet Hari Ini
        $omzetHariIni = \App\Models\Sale::whereDate('date', $today)->sum('total_amount');

        $storeId = auth()->user()->store_id;
        $isSuperAdmin = auth()->user()->role === 'super_admin';

        // Laba Hari Ini
        $labaHariIni = \Illuminate\Support\Facades\DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereDate('sales.date', $today)
            ->when(!$isSuperAdmin, function ($query) use ($storeId) {
                return $query->where('sales.store_id', $storeId);
            })
            ->sum(\Illuminate\Support\Facades\DB::raw('(sale_items.price - sale_items.purchase_price) * sale_items.quantity'));

        // Total Transaksi Hari Ini
        $totalTransaksi = \App\Models\Sale::whereDate('date', $today)->count();

        // Pengeluaran Kulakan Hari Ini
        $pengeluaranHariIni = \App\Models\Purchase::whereDate('date', $today)->sum('total_amount');

        // Stok Kritis (Produk yang perlu dikulak)
        $stokKritis = \App\Models\Product::whereRaw('stock <= min_stock')->orderBy('stock', 'asc')->limit(5)->get();

        // Cek apakah ada transaksi hari ini yang menggunakan custom item
        $hasCustomItems = \Illuminate\Support\Facades\DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereDate('sales.date', $today)
            ->whereNull('sale_items.product_id')
            ->when(!$isSuperAdmin, function ($query) use ($storeId) {
                return $query->where('sales.store_id', $storeId);
            })
            ->exists();
            
        // Produk dengan stok minus (perlu disesuaikan)
        $stokMinus = \App\Models\Product::where('stock', '<', 0)->get();

        return view('home', compact(
            'omzetHariIni', 
            'labaHariIni', 
            'totalTransaksi', 
            'pengeluaranHariIni', 
            'stokKritis',
            'hasCustomItems',
            'stokMinus'
        ));
    }

    public function upgrade()
    {
        $packages = \App\Models\Package::all();
        $subscription = auth()->user()->subscription;
        $now = \Carbon\Carbon::now();
        $isActive = $subscription && $subscription->package && \Carbon\Carbon::parse($subscription->end_date)->greaterThanOrEqualTo($now);
        
        return view('upgrade', compact('packages', 'subscription', 'isActive'));
    }
}
