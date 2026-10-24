<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::create([
            'shop_name' => 'Toko Es Teh Manis',
            'address' => 'Jl. Kebon Jeruk No. 123, Jakarta Barat',
            'phone' => '081234567890',
            'footer_text' => 'Terima kasih atas kunjungan Anda!
Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.',
        ]);
    }
}
