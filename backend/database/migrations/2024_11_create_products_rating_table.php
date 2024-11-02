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
    Schema::create('products_rating', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained(table: 'products')->cascadeOnDelete();
      $table->foreignId('user_id')->constrained(table: 'users')->cascadeOnDelete();
      $table->text('description')->nullable();
      $table->string('pros', config('constants.max_pros_cons_length'))->nullable();
      $table->string('cons', config('constants.max_pros_cons_length'))->nullable();
      $table->unsignedSmallInteger('value');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('products_rating');
  }
};
