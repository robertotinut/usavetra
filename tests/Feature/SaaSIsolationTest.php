<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class SaaSIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_data_isolation()
    {
        // Setup Stores (ID 1 is already created by migration)
        DB::table('stores')->insert([
            ['id' => 2, 'name' => 'Toko A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Toko B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Setup Users
        $userA = User::create([
            'name' => 'Admin Toko A',
            'email' => 'a@toko.com',
            'password' => bcrypt('password'),
            'store_id' => 2,
        ]);

        $userB = User::create([
            'name' => 'Admin Toko B',
            'email' => 'b@toko.com',
            'password' => bcrypt('password'),
            'store_id' => 3,
        ]);

        // Login as User A and create a category
        $this->actingAs($userA);
        $categoryA = Category::create([
            'name' => 'Kategori Toko A',
            'description' => 'Ini kategori milik A'
        ]);

        // Verify Category A has store_id = 2
        $this->assertEquals(2, $categoryA->store_id);

        // Verify User A can see Category A
        $this->assertCount(1, Category::all());
        
        // Logout User A
        auth()->logout();

        // Login as User B
        $this->actingAs($userB);
        
        // Verify User B CANNOT see Category A
        $this->assertCount(0, Category::all());

        // User B creates their own category
        $categoryB = Category::create([
            'name' => 'Kategori Toko B',
            'description' => 'Ini kategori milik B'
        ]);

        // Verify Category B has store_id = 3
        $this->assertEquals(3, $categoryB->store_id);
        
        // Verify User B can see only Category B
        $this->assertCount(1, Category::all());
        $this->assertEquals('Kategori Toko B', Category::first()->name);
    }
}
