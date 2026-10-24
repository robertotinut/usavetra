<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all(); // Nanti akan diubah jadi AJAX jika produk sangat banyak
        
        // Generate Invoice Number
        $lastPurchase = Purchase::latest()->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
        $storeId = auth()->user()->store_id ?? 0;
        $invoiceNumber = 'INV' . $storeId . '-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'invoice_number' => 'required|string|unique:purchases,invoice_number',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan header Purchase
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'total_amount' => $request->total_amount,
                'notes' => $request->notes,
            ]);

            // 2. Simpan detail & Update Stok
            foreach ($request->products as $item) {
                $subtotal = $item['quantity'] * $item['price'];
                
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Update Stok & Harga Beli Produk
                $product = Product::find($item['id']);
                
                // Catat pergerakan stok
                StockMovement::create([
                    'product_id' => $product->id,
                    'reference' => $purchase->invoice_number,
                    'type' => 'in',
                    'qty_before' => $product->stock,
                    'qty_change' => $item['quantity'],
                    'qty_after' => $product->stock + $item['quantity'],
                    'notes' => 'Pembelian / Kulakan',
                ]);
                
                $product->stock += $item['quantity'];
                $product->purchase_price = $item['price']; // Update HPP dengan harga terbaru
                $product->save();
            }

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Transaksi Kulakan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('items.product', 'supplier');
        return view('purchases.show', compact('purchase'));
    }

    public function destroy(Purchase $purchase)
    {
        try {
            DB::beginTransaction();

            // Kembalikan stok untuk setiap item
            foreach ($purchase->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    StockMovement::create([
                        'product_id' => $product->id,
                        'reference' => $purchase->invoice_number,
                        'type' => 'out',
                        'qty_before' => $product->stock,
                        'qty_change' => -$item->quantity,
                        'qty_after' => $product->stock - $item->quantity,
                        'notes' => 'Batal Kulakan',
                    ]);

                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }

            $purchase->delete(); // Otomatis cascade delete item jika ada relasi, atau biarkan item terhapus

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Transaksi kulakan berhasil dibatalkan dan stok dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchases.index')->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
