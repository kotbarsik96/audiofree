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
    Schema::create('user_product_orders', function (Blueprint $table) {
      $table->id();
      $table->unsignedInteger('quantity');
      $table->foreignId('order_id')->constrained(table: 'orders')->cascadeOnDelete();
      $table->foreignId('product_id')->nullable()->constrained(table: 'products')->nullOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('user_product_orders');
  }
};
