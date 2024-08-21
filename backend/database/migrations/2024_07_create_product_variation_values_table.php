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
    Schema::create('product_variation_values', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained(table: 'products')
        ->cascadeOnDelete();
      $table->string('value');
      $table->unsignedInteger('price');
      $table->unsignedSmallInteger('discount')->nullable();
      $table->string('image_path')->nullable();
      $table->unsignedInteger('quantity');
      $table->timestamps();

      $table->foreign('image_path')
        ->references('path')->on('images')->cascadeOnUpdate()->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_variation_values');
  }
};
