<?php

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
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
            $table->foreignId('chat_id')->constrained('support_chats');
            $table->foreignId('author_id')->constrained('users', 'id');
            $table->enum('sender_type', SupportChatSenderTypeEnum::values());
            $table->text('text');
            $table->json('replaces_user')
                ->nullable()
                ->comment('Список заменяемых фраз для пользователя. Подставляются только в системные сообщения (sender_type == system) и выглядят так: { "user": "Пользователь" }. Заменяет соответствующую подстроку в строке ":user: присоединился к чату" -> "Пользователь присоединился к чату"');
            $table->json('replaces_staff')
                ->nullable()
                ->comment('Список заменяемых фраз для сотрудников');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_chat_messages');
    }
};
