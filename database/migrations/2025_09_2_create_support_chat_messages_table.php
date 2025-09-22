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
    Schema::create('support_chat_messages', function (Blueprint $table) {
      $table->id();
      $table->foreignId('chat_id')->constrained(table: 'support_chats');
      $table->foreignId('message_author');
      $table->text('message_text');
      $table->boolean('was_read')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('support_chat');
  }
};
