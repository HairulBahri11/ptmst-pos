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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->date('tanggal');
            $table->foreignId('customer_id')->references('id')->on('customer');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon', 15, 2)->nullable();
            $table->decimal('ongkir', 15, 2)->nullable();
            $table->decimal('total_bayar', 15, 2);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
