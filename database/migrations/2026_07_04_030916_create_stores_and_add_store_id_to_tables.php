<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create stores table
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Create a default store for existing records
        DB::table('stores')->insert([
            'id' => 1,
            'name' => 'Default Store (Legacy)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tables = [
            'users', 'categories', 'suppliers', 
            'purchases', 'purchase_items', 'sales', 
            'sale_items', 'stock_movements', 'profiles'
        ];

        // 2. Add store_id to other tables
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Tambah field nullable dulu
                $table->foreignId('store_id')->nullable()->constrained()->cascadeOnDelete();
            });

            // Set ke default store
            DB::table($tableName)->update(['store_id' => 1]);

            // Buat jadi not null (optional, better not null)
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('store_id')->nullable(false)->change();
            });
        }

        // Khusus untuk products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->constrained()->cascadeOnDelete();
        });
        DB::table('products')->update(['store_id' => 1]);
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('store_id')->nullable(false)->change();
            
            // Drop unique barcode lama
            $table->dropUnique(['barcode']);
            // Tambahkan composite unique
            $table->unique(['store_id', 'barcode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['store_id', 'barcode']);
            $table->unique('barcode');
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });

        $tables = [
            'users', 'categories', 'suppliers', 
            'purchases', 'purchase_items', 'sales', 
            'sale_items', 'stock_movements', 'profiles'
        ];

        foreach (array_reverse($tables) as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['store_id']);
                $table->dropColumn('store_id');
            });
        }

        Schema::dropIfExists('stores');
    }
};
