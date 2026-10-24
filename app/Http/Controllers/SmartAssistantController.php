<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SmartAssistantController extends Controller
{
    public function index()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Ambil semua produk
        $products = Product::all();

        // Hitung total penjualan 30 hari terakhir per produk
        $salesData = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy('product_id')
            ->pluck('total_sold', 'product_id');

        $restockRecommendations = [];
        $criticalMargins = [];
        $totalLosses = 0;

        foreach ($products as $product) {
            $totalSold = $salesData[$product->id] ?? 0;
            
            // 1. Analisis Velocity (Kecepatan Terjual per hari)
            // Karena ini UMKM, kita asumsikan minimum 1 terjual dalam sebulan agar tidak 0
            $dailyVelocity = $totalSold > 0 ? $totalSold / 30 : 0;
            
            // 2. Prediksi sisa hari stok habis
            $daysRemaining = 999; // Default aman
            if ($dailyVelocity > 0) {
                $daysRemaining = round($product->stock / $dailyVelocity);
            }

            // Jika stok akan habis dalam <= 7 hari ATAU stok sudah 0 dan barangnya laku
            if ($daysRemaining <= 7 || ($product->stock <= 0 && $totalSold > 0)) {
                // Sarankan beli untuk kebutuhan 30 hari ke depan
                $suggestedRestock = ceil($dailyVelocity * 30) - $product->stock;
                if ($suggestedRestock < 0) $suggestedRestock = 0;

                if ($suggestedRestock > 0) {
                    $restockRecommendations[] = [
                        'product' => $product,
                        'velocity' => round($dailyVelocity, 2),
                        'days_remaining' => $daysRemaining < 0 ? 0 : $daysRemaining,
                        'suggested_restock' => $suggestedRestock
                    ];
                }
            }

            // 3. Analisis Margin (Bocor)
            $margin = $product->selling_price - $product->purchase_price;
            $marginPercent = $product->purchase_price > 0 ? ($margin / $product->purchase_price) * 100 : 100;
            
            if ($margin <= 0) {
                $totalLosses++;
                $criticalMargins[] = [
                    'product' => $product,
                    'margin' => $margin,
                    'margin_percent' => round($marginPercent, 1),
                    'status' => 'Rugi'
                ];
            } elseif ($marginPercent < 10) {
                // Margin di bawah 10% dianggap terlalu mepet
                $criticalMargins[] = [
                    'product' => $product,
                    'margin' => $margin,
                    'margin_percent' => round($marginPercent, 1),
                    'status' => 'Mepet'
                ];
            }
        }

        // Urutkan rekomendasi dari yang paling mendesak
        usort($restockRecommendations, function($a, $b) {
            return $a['days_remaining'] <=> $b['days_remaining'];
        });

        // Urutkan margin kritis dari yang paling parah
        usort($criticalMargins, function($a, $b) {
            return $a['margin_percent'] <=> $b['margin_percent'];
        });

        return view('smart_assistant.index', compact('restockRecommendations', 'criticalMargins', 'totalLosses'));
    }
}
