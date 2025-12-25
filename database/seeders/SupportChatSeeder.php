<?php



namespace Database\Seeders;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SupportChatSeeder extends Seeder
{
    public function run(): void
    {
        User::whereNotIn('id', function ($query) {
            $query->select('users.id')
                ->from('users')
                ->join('support_chats', 'support_chats.user_id', '=', 'users.id');
        })->chunk(50, function (Collection $users) {
            foreach ($users as $user) {
                $supportChat = SupportChat::create([
                    'user_id' => $user->id,
                    'status' => SupportChatStatusesEnum::OPEN->value
                ]);

                SupportChatMessage::create([
                    'chat_id' => $supportChat->id,
                    'author_id' => $user->id,
                    'sender_type' => SupportChatSenderTypeEnum::USER->value,
                    'text' => 'Здравствуйте',
                    'read_at' => null
                ]);
            }
        });
    }
}
