<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
  public function up(): void
  {
    Schema::create('telegraph_chats', function (Blueprint $table) {
      $table->id();
      $table->string('chat_id');
      $table->string('name')->nullable();
      $table->foreignId('user_id')->nullable()
        ->constrained('users')->cascadeOnDelete();
      $table->string('state')->nullable();

      $table->foreignId('telegraph_bot_id')
        ->constrained('telegraph_bots')->cascadeOnDelete();
      $table->timestamps();

      $table->unique(['chat_id', 'telegraph_bot_id', 'user_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('telegraph_chats');
  }
};
