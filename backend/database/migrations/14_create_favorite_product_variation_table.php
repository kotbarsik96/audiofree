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
    Schema::create('favorite_product_variation', function (Blueprint $table) {
      $table->foreignId('favorite_product_id')->constrained(table: 'favorites');
      $table->foreignId('variation_id')->nullable()->constrained(table: 'products_variations')
        ->nullOnDelete();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('favorite_product_variation');
  }
};
