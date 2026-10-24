<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('reference')->nullable()->comment('Contoh: TRC-2023..., INV-2023...');
            $table->enum('type', ['in', 'out', 'adj'])->comment('in=Masuk, out=Keluar, adj=Penyesuaian');
            $table->integer('qty_before');
            $table->integer('qty_change');
            $table->integer('qty_after');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
