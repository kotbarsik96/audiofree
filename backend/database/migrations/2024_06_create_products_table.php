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
    Schema::create('products', function (Blueprint $table) {
      $table->id();
      $table->string("name");
      $table->text('description')->nullable();
      $table->string('status')->nullable();
      $table->string('brand')->nullable();
      $table->string('category')->nullable();
      $table->string('type')->nullable();
      $table->string('image_path')->nullable();
      $table->foreignId('created_by')->nullable()->constrained(table: 'users')->nullOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained(table: 'users')->nullOnDelete();
      $table->timestamps();

      $table->foreign('status')
        ->references('name')->on('taxonomies')->cascadeOnUpdate()->nullOnDelete();
      $table->foreign('brand')
        ->references('name')->on('taxonomies')->cascadeOnUpdate()->nullOnDelete();
      $table->foreign('category')
        ->references('name')->on('taxonomies')->cascadeOnUpdate()->nullOnDelete();
      $table->foreign('type')
        ->references('name')->on('taxonomies')->cascadeOnUpdate()->nullOnDelete();
      $table->foreign('image_path')
        ->references('path')->on('images')->cascadeOnUpdate()->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products');
  }
};
