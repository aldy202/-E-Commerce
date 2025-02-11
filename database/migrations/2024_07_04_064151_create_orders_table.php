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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('id_member');
            $table->integer('invoice');
            $table->integer('total');
            $table->timestamps();
        });

        Schema::create('orders_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('id_order');
            $table->integer('id_product');
            $table->integer('jumlah');
            $table->integer('size');
            $table->integer('color');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
