<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_dashboard_shows_correct_metrics()
    {
        $category = Category::create(['name' => 'Test']);
        
        // Produk dengan stok kritis (1)
        $product1 = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk A',
            'purchase_price' => 1000,
            'selling_price' => 2000,
            'stock' => 1,
            'min_stock' => 5,
        ]);
        
        // Produk dengan stok aman (10)
        $product2 = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk B',
            'purchase_price' => 5000,
            'selling_price' => 7000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $today = date('Y-m-d');

        // Buat Penjualan (Omzet = 11000, Laba = 1000 + 4000 = 5000)
        $sale = Sale::create([
            'receipt_number' => 'TRC-1',
            'date' => $today,
            'total_amount' => 11000,
            'paid_amount' => 15000,
            'change_amount' => 4000
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product1->id,
            'quantity' => 1,
            'price' => 2000, // profit = 1000
            'purchase_price' => 1000,
            'subtotal' => 2000
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 9000, // profit = 4000
            'purchase_price' => 5000,
            'subtotal' => 9000
        ]);

        // Buat Pembelian / Kulakan (Pengeluaran = 5000)
        Purchase::create([
            'invoice_number' => 'INV-1',
            'date' => $today,
            'total_amount' => 5000
        ]);

        $response = $this->actingAs($this->user)->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('omzetHariIni', 11000);
        $response->assertViewHas('labaHariIni', 5000); // (2000-1000)*1 + (9000-5000)*1
        $response->assertViewHas('totalTransaksi', 1);
        $response->assertViewHas('pengeluaranHariIni', 5000);
        $response->assertViewHas('stokKritis', function($stok) use ($product1) {
            return $stok->contains($product1) && $stok->count() === 1;
        });
    }
}
