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
    Schema::create('galleries_images', function (Blueprint $table) {
      $table->foreignId('gallery_id')->constrained(table: 'galleries')->cascadeOnDelete();
      $table->string('image_path');
      $table->integer('order')->nullable();

      $table->foreign('image_path')->references('path')->on('images')
        ->cascadeOnDelete()->cascadeOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('galleries_images');
  }
};
