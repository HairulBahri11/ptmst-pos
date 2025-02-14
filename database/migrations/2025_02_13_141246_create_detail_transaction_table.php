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
        Schema::create('detail_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->references('id')->on('transaction')->onDelete('cascade');
            $table->foreignId('food_id')->references('id')->on('food')->onDelete('cascade');
            $table->integer('qty');
            $table->decimal('harga_bandrol', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->nullable();
            $table->decimal('harga_diskon', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaction');
    }
};
