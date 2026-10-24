<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Kategori
        $katMakanan = Category::create([
            'name' => 'Makanan Ringan',
            'description' => 'Segala jenis snack dan kerupuk'
        ]);

        $katMinuman = Category::create([
            'name' => 'Minuman',
            'description' => 'Minuman dingin dan kemasan'
        ]);

        $katSembako = Category::create([
            'name' => 'Sembako',
            'description' => 'Beras, gula, minyak goreng'
        ]);

        // Buat Supplier
        $supIndofood = Supplier::create([
            'name' => 'PT Indofood CBP',
            'phone' => '08111222333',
            'address' => 'Jakarta Pusat'
        ]);

        $supWings = Supplier::create([
            'name' => 'Wings Group',
            'phone' => '08222333444',
            'address' => 'Surabaya'
        ]);

        // Buat Produk
        Product::create([
            'category_id' => $katMakanan->id,
            'supplier_id' => $supIndofood->id,
            'barcode' => '899999900001',
            'name' => 'Indomie Goreng Original',
            'purchase_price' => 2500,
            'selling_price' => 3000,
            'stock' => 100,
            'min_stock' => 20
        ]);

        Product::create([
            'category_id' => $katMinuman->id,
            'supplier_id' => null,
            'barcode' => '899999900002',
            'name' => 'Aqua Botol 600ml',
            'purchase_price' => 2800,
            'selling_price' => 3500,
            'stock' => 50,
            'min_stock' => 10
        ]);

        Product::create([
            'category_id' => $katSembako->id,
            'supplier_id' => null,
            'barcode' => '899999900003',
            'name' => 'Beras Raja Lele 5Kg',
            'purchase_price' => 60000,
            'selling_price' => 65000,
            'stock' => 10,
            'min_stock' => 5
        ]);

        Product::create([
            'category_id' => $katMinuman->id,
            'supplier_id' => $supWings->id,
            'barcode' => '899999900004',
            'name' => 'Floridina Orange',
            'purchase_price' => 2500,
            'selling_price' => 3000,
            'stock' => 24,
            'min_stock' => 12
        ]);
        
        Product::create([
            'category_id' => $katSembako->id,
            'supplier_id' => null,
            'barcode' => '899999900005',
            'name' => 'Gula Pasir 1Kg',
            'purchase_price' => 15000,
            'selling_price' => 17000,
            'stock' => 2, // Stok menipis untuk tes UI
            'min_stock' => 10
        ]);
    }
}
