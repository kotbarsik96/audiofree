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
    Schema::create('product_variations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained(table: 'products')
        ->cascadeOnDelete();
      $table->string('name');
      $table->unsignedInteger('image_id')->nullable();
      $table->unsignedInteger('price');
      $table->unsignedSmallInteger('discount')->nullable();
      $table->unsignedInteger('quantity');
      $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
      $table->timestamps();

      $table->foreign('image_id')
        ->references('id')
        ->on('attachments')
        ->cascadeOnUpdate()
        ->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('product_variations');
  }
};
