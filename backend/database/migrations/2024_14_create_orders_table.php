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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained(table: 'users')->cascadeOnDelete();
      $table->string('status')->nullable();
      $table->string('address');
      $table->text('comment')->nullable();
      $table->string('name');
      $table->string('email');
      $table->string('phone_number');
      $table->timestamps();

      $table->foreign('status')->references('name')->on('taxonomies')
        ->nullOnDelete()
        ->cascadeOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
