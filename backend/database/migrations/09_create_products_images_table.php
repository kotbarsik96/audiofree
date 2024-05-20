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
    Schema::create('products_images', function (Blueprint $table) {
      $table->foreignId('product_id')->constrained(table: 'products')->cascadeOnDelete();
      $table->string('image_path');
      $table->timestamps();

      $table->foreign('image_path')
        ->references('path')->on('images')->cascadeOnDelete()->cascadeOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products_images');
  }
};
