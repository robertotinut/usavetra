<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->latest()->get();
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Pengecekan limit produk untuk akun Free
        if (Auth::user()->role !== 'super_admin') {
            $subscription = Auth::user()->subscription;
            $hasUnlimited = $subscription && $subscription->package && in_array('unlimited_produk', $subscription->package->features);
            
            if (!$hasUnlimited) {
                $totalProducts = Product::count();
                if ($totalProducts >= 50) {
                    return back()->with('premium_error', 'Batas maksimal 50 produk telah tercapai. Tingkatkan paket berlangganan (Premium) untuk menambah produk tanpa batas.');
                }
            }
        }

        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', Auth::user()->store_id);
                })
            ],
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($request->all());
        $product->update([
            'product_code' => 'PRD-' . str_pad($product->id, 4, '0', STR_PAD_LEFT)
        ]);

        if ($product->stock != 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'reference' => 'Sistem',
                'type' => 'adj',
                'qty_before' => 0,
                'qty_change' => $product->stock,
                'qty_after' => $product->stock,
                'notes' => 'Stok Awal Produk Baru',
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($product->id)->where(function ($query) {
                    return $query->where('store_id', Auth::user()->store_id);
                })
            ],
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer',
            'min_stock' => 'required|integer|min:0',
        ]);

        $oldStock = $product->stock;
        $product->update($request->all());

        if ($oldStock != $request->stock) {
            StockMovement::create([
                'product_id' => $product->id,
                'reference' => 'Manual',
                'type' => 'adj',
                'qty_before' => $oldStock,
                'qty_change' => $request->stock - $oldStock,
                'qty_after' => $request->stock,
                'notes' => 'Suntik Stok / Penyesuaian Manual',
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function show(Product $product)
    {
        $hasPremium = Auth::user()->role === 'super_admin' || (Auth::user()->subscription && Auth::user()->subscription->package && in_array('kartu_stok', Auth::user()->subscription->package->features));
        
        $query = StockMovement::where('product_id', $product->id)->latest();
        
        if (!$hasPremium) {
            $query->limit(3);
        }
        
        $movements = $query->get();
        return view('products.show', compact('product', 'movements', 'hasPremium'));
    }
}
