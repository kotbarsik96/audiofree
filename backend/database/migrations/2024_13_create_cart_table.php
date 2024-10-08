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
    Schema::create('cart', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained(table: 'users')
        ->cascadeOnDelete();
      $table->foreignId('variation_id')->constrained(table: 'product_variations')
        ->cascadeOnDelete();
      $table->unsignedInteger('quantity');
      $table->boolean('is_oneclick');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('favorites');
  }
};
