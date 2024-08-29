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
    Schema::create('order_products', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained(table: 'orders')
        ->cascadeOnDelete();
      $table->foreignId('product_id')->nullable()->constrained(table: 'products')
        ->nullOnDelete();
      $table->foreignId('variation_id')->nullable()->constrained(table: 'product_variations')
        ->nullOnDelete();
      $table->unsignedInteger('quantity');
      $table->unsignedSmallInteger('discount');
      $table->unsignedInteger('original_price');
      $table->unsignedInteger('price');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_products');
  }
};
