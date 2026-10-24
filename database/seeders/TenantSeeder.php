<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Store;
use App\Models\User;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Paket Premium
        $package = Package::firstOrCreate(
            ['name' => 'Premium SaaS - 1 Bulan'],
            [
                'price' => 55000,
                'duration_days' => 30,
                'is_active' => true,
                'features' => ['laporan_keuangan', 'unlimited_produk', 'kartu_stok', 'fitur_kulakan']
            ]
        );

        // --- TOKO A (PREMIUM) ---
        $storeA = Store::create(['name' => 'Toko Alat Tulis (Premium)']);
        $userA = User::create([
            'name' => 'Budi ATK',
            'email' => 'premium@toko-a.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'store_id' => $storeA->id,
        ]);

        Subscription::create([
            'store_id' => $storeA->id,
            'package_id' => $package->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($package->duration_days),
            'status' => 'active'
        ]);

        // --- TOKO B (FREE) ---
        $storeB = Store::create(['name' => 'Warkop Pak Eko (Free)']);
        $userB = User::create([
            'name' => 'Pak Eko',
            'email' => 'free@toko-b.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'store_id' => $storeB->id,
        ]);

        // Seeding Data Toko A (Premium)
        // Bypass global scope manual untuk proses seeding
        $this->seedTenantData($storeA->id, 'Alat Tulis');
        
        // Seeding Data Toko B (Free)
        $this->seedTenantData($storeB->id, 'Warkop');
    }

    private function seedTenantData($storeId, $type)
    {
        // Kategori
        $cat1 = Category::create(['store_id' => $storeId, 'name' => ($type == 'Alat Tulis') ? 'Buku' : 'Kopi']);
        $cat2 = Category::create(['store_id' => $storeId, 'name' => ($type == 'Alat Tulis') ? 'Bolpoin' : 'Makanan']);
        $categories = [$cat1->id, $cat2->id];

        // Supplier
        $sup1 = Supplier::create(['store_id' => $storeId, 'name' => 'Grosir ' . $type]);

        // Produk
        for ($i = 1; $i <= 10; $i++) {
            Product::create([
                'store_id' => $storeId,
                'product_code' => 'PRD-' . $storeId . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Produk ' . $type . ' ' . $i,
                'category_id' => $categories[array_rand($categories)],
                'purchase_price' => rand(10, 50) * 100,
                'selling_price' => rand(60, 100) * 100,
                'stock' => rand(5, 50),
                'min_stock' => 5,
            ]);
        }
    }
}
