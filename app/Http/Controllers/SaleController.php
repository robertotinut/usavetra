<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::with('category')->get(); 
        $categories = \App\Models\Category::all();
        // Semua produk ditampilkan, stok minus diizinkan (fitur Suntik Stok)
        
        $lastSale = Sale::latest()->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        $storeId = auth()->user()->store_id ?? 0;
        $receiptNumber = 'TRC' . $storeId . '-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('sales.create', compact('products', 'categories', 'receiptNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receipt_number' => 'required|string|unique:sales,receipt_number',
            'date' => 'required|date',
            'customer_name' => 'nullable|string|max:100',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'paid_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required',
            'products.*.is_custom' => 'nullable|boolean',
            'products.*.name' => 'nullable|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // Validasi pembayaran cukup
        if ($request->paid_amount < $request->total_amount) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Jumlah bayar kurang dari total belanja!'], 400);
            }
            return back()->with('error', 'Jumlah bayar kurang dari total belanja!')->withInput();
        }

        try {
            DB::beginTransaction();

            $change_amount = $request->paid_amount - $request->total_amount;

            // 1. Simpan header Sale
            $sale = Sale::create([
                'receipt_number' => $request->receipt_number,
                'date' => $request->date,
                'customer_name' => $request->customer_name,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $change_amount,
            ]);

            $updatedStocks = [];
            // 2. Simpan detail & Potong Stok
            foreach ($request->products as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                $isCustom = isset($item['is_custom']) && $item['is_custom'];

                if ($isCustom) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => null,
                        'custom_name' => $item['name'] ?? 'Item Manual',
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'purchase_price' => 0, // HPP 0 untuk item manual
                        'subtotal' => $subtotal,
                    ]);
                } else {
                    $product = Product::find($item['id']);
                    if (!$product) continue;
                    
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'purchase_price' => $product->purchase_price,
                        'subtotal' => $subtotal,
                    ]);

                    // Potong Stok
                    StockMovement::create([
                        'product_id' => $product->id,
                        'reference' => $sale->receipt_number,
                        'type' => 'out',
                        'qty_before' => $product->stock,
                        'qty_change' => -$item['quantity'],
                        'qty_after' => $product->stock - $item['quantity'],
                        'notes' => 'Penjualan (POS)',
                    ]);

                    $product->stock -= $item['quantity'];
                    $product->save();
                    
                    $updatedStocks[$product->id] = $product->stock;
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                $nextId = $sale->id + 1;
                $newReceiptNumber = 'TRC-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Transaksi Penjualan berhasil disimpan ke laporan.',
                    'new_receipt' => $newReceiptNumber,
                    'sale_id' => $sale->id,
                    'updated_stocks' => $updatedStocks
                ]);
            }

            return redirect()->route('sales.index')->with('success', 'Transaksi Penjualan berhasil.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Sale $sale)
    {
        $sale->load('items.product');
        return view('sales.show', compact('sale'));
    }

    public function print(Sale $sale)
    {
        $sale->load('items.product');
        $store = \App\Models\Store::find($sale->store_id);
        
        // Cek apakah pemilik store ini berlangganan fitur kustom struk (dari user owner toko)
        $owner = \App\Models\User::where('store_id', $sale->store_id)->where('role', 'tenant')->first() ?? auth()->user();
        $hasPremiumStruk = $owner->role === 'super_admin' || ($owner->subscription && $owner->subscription->package && in_array('kustom_struk', $owner->subscription->package->features));
        
        return view('sales.print', compact('sale', 'store', 'hasPremiumStruk'));
    }
}
