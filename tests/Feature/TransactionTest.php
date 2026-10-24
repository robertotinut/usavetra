<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        
        $category = Category::create(['name' => 'Snack']);
        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Chitato',
            'purchase_price' => 5000,
            'selling_price' => 6000,
            'stock' => 10,
            'min_stock' => 2,
        ]);
    }

    public function test_user_can_view_pos_page()
    {
        $response = $this->actingAs($this->user)->get(route('sales.create'));
        $response->assertStatus(200);
    }

    public function test_kulakan_increases_stock()
    {
        $initialStock = $this->product->stock;
        
        $response = $this->actingAs($this->user)->post(route('purchases.store'), [
            'invoice_number' => 'INV-001',
            'date' => date('Y-m-d'),
            'total_amount' => 50000,
            'products' => [
                [
                    'id' => $this->product->id,
                    'quantity' => 10,
                    'price' => 5000,
                ]
            ]
        ]);

        $response->assertRedirect(route('purchases.index'));
        
        // Cek stok bertambah
        $this->product->refresh();
        $this->assertEquals($initialStock + 10, $this->product->stock);
    }

    public function test_pos_sale_decreases_stock()
    {
        $initialStock = $this->product->stock;
        
        $response = $this->actingAs($this->user)->post(route('sales.store'), [
            'receipt_number' => 'TRC-001',
            'date' => date('Y-m-d'),
            'total_amount' => 12000,
            'payment_method' => 'Tunai',
            'paid_amount' => 15000,
            'products' => [
                [
                    'id' => $this->product->id,
                    'quantity' => 2,
                    'price' => 6000,
                ]
            ]
        ]);

        $response->assertRedirect(route('sales.index'));
        
        // Cek stok berkurang
        $this->product->refresh();
        $this->assertEquals($initialStock - 2, $this->product->stock);
        
        // Cek data SaleItem tersimpan dengan purchase_price (HPP)
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $this->product->id,
            'purchase_price' => 5000,
            'subtotal' => 12000
        ]);
    }
}
