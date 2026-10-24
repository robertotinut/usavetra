<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat user untuk login
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_user_can_view_categories_page()
    {
        $response = $this->actingAs($this->user)->get(route('categories.index'));
        $response->assertStatus(200);
    }

    public function test_user_can_create_category()
    {
        $response = $this->actingAs($this->user)->post(route('categories.store'), [
            'name' => 'Minuman',
            'description' => 'Minuman dingin',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Minuman']);
    }

    public function test_user_can_create_supplier()
    {
        $response = $this->actingAs($this->user)->post(route('suppliers.store'), [
            'name' => 'PT Sumber Rejeki',
            'phone' => '08123456789',
            'address' => 'Jakarta',
        ]);

        $response->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseHas('suppliers', ['name' => 'PT Sumber Rejeki']);
    }

    public function test_user_can_create_product()
    {
        $category = Category::create(['name' => 'Snack']);
        
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'name' => 'Chitato',
            'category_id' => $category->id,
            'purchase_price' => 5000,
            'selling_price' => 6000,
            'stock' => 10,
            'min_stock' => 2,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Chitato']);
    }
}
