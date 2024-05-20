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
    Schema::create('order_product_variations', function (Blueprint $table) {
      $table->foreignId('product_order_id')->constrained(table: 'user_product_orders');
      $table->foreignId('variation_id')->nullable()->constrained(table: 'products_variations')
        ->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('order_product_variations');
  }
};
