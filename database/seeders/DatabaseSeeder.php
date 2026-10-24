<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat store untuk Super Admin
        $adminStore = \App\Models\Store::create(['name' => 'Usavetra HQ']);

        // Buat Super Admin (role: superadmin)
        User::create([
            'name' => 'Admin Usavetra',
            'email' => 'admin@usavetra.com',
            'password' => bcrypt('password'),
            'role' => 'super_admin',
            'store_id' => $adminStore->id,
        ]);

        $this->call([
            TenantSeeder::class,
        ]);
    }
}
