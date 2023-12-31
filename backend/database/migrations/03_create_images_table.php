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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('tag')
                ->nullable();
            $table->string('name');
            $table->string('extension');
            $table->integer('size_kb');
            $table->integer('width');
            $table->integer('height');
            $table->string('original_name');
            $table->foreignId('user_id')
                ->constrained(table: 'users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
