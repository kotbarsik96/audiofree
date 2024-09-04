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
    Schema::create('taxonomy_values', function (Blueprint $table) {
      $table->id();
      $table->string('taxonomy_name');
      $table->string('value')->index();
      $table->timestamps();

      $table->foreign('taxonomy_name')->references('name')->on('taxonomies')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('taxonomies');
  }
};
