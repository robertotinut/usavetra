<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_code', 50)->nullable()->unique()->after('id');
        });

        // Set default values for existing rows
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['product_code' => 'PRD-' . str_pad($product->id, 4, '0', STR_PAD_LEFT)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_code');
        });
    }
};
