<?php

namespace App\Models\SupportChat;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Events\SupportChat\SupportChatMessageCreated;

class SupportChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'author_id',
        'sender_type',
        'text',
        'read_at',
        'edited_at',
        'replaces_user',
        'replaces_staff'
    ];

    protected $casts = [
        'replaces_user' => 'json',
        'replaces_staff' => 'json',
    ];

    protected $hidden = [
        'replaces_user',
        'replaces_staff',
        'author',
        'chat'
    ];

    protected $dispatchesEvents = [
        'created' => SupportChatMessageCreated::class
    ];

    public function scopeUnreadMessages(Builder $query)
    {
        return $query->whereNull('read_at');
    }

    public function chat()
    {
        return $this->belongsTo(SupportChat::class, 'chat_id');
    }

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function isSystem()
    {
        return $this->sender_type === SupportChatSenderTypeEnum::SYSTEM->value;
    }

    /** заменяет переменные в системных сообщениях (sender_type === SupportChatSenderTypeEnum::SYSTEM) для вывода пользователю
     * 
     * зарезервированные переменные: 
     * * :__staff_name: - заменяется на строку "Сотрудник"; 
     * * :__user_name: - заменяется на имя пользователя чата
     * 
     * кроме зарезервированных, переменные передаются при создании сообщения в поле replaces_staff (например: { "user": "Пользователь" } заменит подстроку :user: на Пользователь)
     */
    public function replaceTextForUser()
    {
        if ($this->sender_type === SupportChatSenderTypeEnum::SYSTEM->value) {
            $text = $this->text;
            if ($this->replacesUser) {
                foreach ($this->replaces_user as $replaceKey => $replaceValue) {
                    $text = str_replace(":$replaceKey:", $replaceValue, $text);
                }
            }
            $text = str_replace(':__staff_name:', __('chat.staff'), $text);
            $text = str_replace(':__user_name:', $this->chat->user->name, $text);
            $this->text = $text;
        }

        return $this;
    }

    /** заменяет переменные в системных сообщениях (sender_type === SupportChatSenderTypeEnum::SYSTEM) для вывода сотруднику
     * 
     * зарезервированные переменные: 
     * * :__staff_name: - заменяется на имя автора сообщения (автор в таких сообщениях - сотрудник);
     * * :__user_name: - заменяется на имя пользователя чата
     * 
     * кроме зарезервированных, переменные передаются при создании сообщения в поле replaces_staff (например: { "user": "Пользователь" } заменит подстроку :user: на Пользователь)
     */
    public function replaceTextForStaff()
    {
        if ($this->sender_type === SupportChatSenderTypeEnum::SYSTEM->value) {
            $text = $this->text;
            if ($this->replaces_staff) {
                foreach ($this->replaces_staff as $replaceKey => $replaceValue) {
                    $text = str_replace(":$replaceKey:", $replaceValue, $this->text);
                }
            }
            $text = str_replace(':__staff_name:', $this->author->name, $text);
            $text = str_replace(':__user_name:', $this->chat->user->name, $text);
            $this->text = $text;
        }

        return $this;
    }
}
