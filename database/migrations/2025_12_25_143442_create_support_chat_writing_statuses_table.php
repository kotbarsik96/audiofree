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
        Schema::create('support_chat_writing_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('support_chats');
            $table->foreignId('writer_id')->constrained('users');
            $table->timestamp('started_writing_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_chat_writing_statuses');
    }
};
