<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('orders_products', function (Blueprint $table) {
      $table->id();
      $table->foreignId('order_id')->constrained('orders');
      $table->foreignId('product_variation_id')->constrained('product_variations');
      $table->string('product_name');
      $table->unsignedInteger('product_quantity');
      $table->mediumInteger('product_price')
        ->comment('Цена за штуку');
      $table->mediumInteger('product_total_cost')
        ->comment('Общая цена за все штуки');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders_products');
  }
};
