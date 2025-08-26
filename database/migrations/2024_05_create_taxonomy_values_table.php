<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('taxonomy_values', function (Blueprint $table) {
      $table->id();
      $table->string('slug');
      $table->string('value')->index();
      $table->string('value_slug');
      $table->timestamps();

      $table->foreign('slug')->references('slug')->on('taxonomies')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();

      $table->fullText('value');
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
