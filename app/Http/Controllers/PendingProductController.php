<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SaleItem;

class PendingProductController extends Controller
{
    public function index()
    {
        // Ambil nama-nama item manual yang unik dan jumlah transaksinya
        $pendingItems = DB::table('sale_items')
            ->select('custom_name', DB::raw('MAX(price) as max_price'), DB::raw('COUNT(*) as total_sold'))
            ->whereNull('product_id')
            ->whereNotNull('custom_name')
            ->groupBy('custom_name')
            ->get();

        return view('pending_products.index', compact('pendingItems'));
    }

    public function create(Request $request)
    {
        $customName = $request->get('name');
        $price = $request->get('price');
        
        if (!$customName) {
            return redirect()->route('pending-products.index')->with('error', 'Item tidak valid.');
        }

        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('pending_products.create', compact('customName', 'price', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'custom_name_original' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Buat produk baru
            $product = Product::create($request->except('custom_name_original'));
            $product->update([
                'product_code' => 'PRD-' . str_pad($product->id, 4, '0', STR_PAD_LEFT)
            ]);

            // 2. Backward Sync: Update semua sale_items lama yang menggunakan nama custom ini
            $customNameOriginal = $request->custom_name_original;
            
            // Kita juga perlu mengupdate purchase_price lama menjadi HPP produk baru agar laba/rugi terkoreksi
            // Namun, jika user mau HPP lama tetap 0 tidak masalah. Lebih baik diupdate agar laporan tidak rancu.
            DB::table('sale_items')
                ->whereNull('product_id')
                ->where('custom_name', $customNameOriginal)
                ->update([
                    'product_id' => $product->id,
                    'custom_name' => null,
                    'purchase_price' => $product->purchase_price // Optional: koreksi HPP
                ]);

            DB::commit();

            return redirect()->route('pending-products.index')->with('success', 'Produk berhasil dibuat dan seluruh transaksi lama telah disinkronisasi!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memproses sinkronisasi: ' . $e->getMessage())->withInput();
        }
    }
}
