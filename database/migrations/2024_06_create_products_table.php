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
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->unsignedInteger('image_id')->nullable();
      $table->foreignId('status_id')->nullable()->constrained('taxonomy_values', 'id')->nullOnDelete();
      $table->foreignId('brand_id')->nullable()->constrained('taxonomy_values', 'id')->nullOnDelete();
      $table->foreignId('category_id')->nullable()->constrained('taxonomy_values', 'id')->nullOnDelete();
      $table->foreignId('type_id')->nullable()->constrained('taxonomy_values', 'id')->nullOnDelete();
      $table->foreignId('created_by')->nullable()->constrained(table: 'users')->nullOnDelete();
      $table->foreignId('updated_by')->nullable()->constrained(table: 'users')->nullOnDelete();
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
    Schema::dropIfExists('products');
  }
};
