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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 50)->unique();
            $table->date('date');
            $table->string('customer_name', 100)->nullable();
            $table->integer('total_amount')->default(0);
            $table->string('payment_method', 50)->default('Tunai');
            $table->integer('paid_amount')->default(0);
            $table->integer('change_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
