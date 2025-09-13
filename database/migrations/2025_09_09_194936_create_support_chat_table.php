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
        Schema::create('support_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->comment('Пользователь, обратившийся в поддержку');
            $table->foreignId('message_author')
                ->comment('Кто написал сообщение');
            $table->text('message_text')
                ->comment('Сообщение');
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
