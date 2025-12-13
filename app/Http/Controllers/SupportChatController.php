<?php

namespace App\Http\Controllers;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Http\Requests\SupportChat\SupportChatGetListRequest;
use App\Http\Requests\SupportChat\SupportChatGetMessagesRequest;
use App\Http\Requests\SupportChat\SupportChatInfoRequest;
use App\Http\Requests\SupportChat\SupportChatMarkAsReadRequest;
use App\Http\Requests\SupportChat\SupportChatWriteMessageRequest;
use App\Models\SupportChat;
use App\Models\SupportChatMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\SupportChat\SupportChatChangeStatusRequest;

class SupportChatController extends Controller
{
    public function getChatInfo(SupportChatInfoRequest $request)
    {
        $chat = null;

        if ($request->has('chat_id')) {
            $chat = SupportChat::find($request->get('chat_id'));
        } else {
            $chat = auth()->user()->supportChat;
        }

        throw_if(!$chat, new NotFoundHttpException(__('abortions.chatNotFound')));

        return response([
            'data' => [
                'unread_messages' => $chat->unreadMessages()->count(),
                'total_messages' => $chat->messages()->count(),
                'first_message_id' => SupportChatMessage::where('chat_id', $chat->id)->first()?->id,
                'last_message_id' => SupportChatMessage::where('chat_id', $chat->id)->orderBy('created_at', 'desc')->first()->id
            ],
            'ok' => true,
        ]);
    }

    public function getMessages(SupportChatGetMessagesRequest $request)
    {
        $chat = null;
        $messages = [];
        $limit = 15;

        if ($request->has('chat_id')) {
            $chat = SupportChat::find($request->chat_id);
        } else {
            $chat = auth()->user()->supportChat;
        }

        // загрузить предыдущие сообщения
        if ($request->has('earliest_message_id')) {
            $earliestMessage = SupportChatMessage::find($request->earliest_message_id);

            $messages = SupportChatMessage::where('chat_id', $chat->id)
                ->where('created_at', '<', $earliestMessage->created_at)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->get()
                ->reverse()
                ->values();
        }
        // загрузить новые сообщения
        elseif ($request->has('latest_message_id')) {
            $latestMessage = SupportChatMessage::find($request->latest_message_id);
            $messages = SupportChatMessage::where('chat_id', $chat->id)
                ->where('created_at', '>', $latestMessage->created_at)
                ->limit($limit)
                ->get()
                ->values();
        } else {
            $oldestUnreadMessage = $chat->unreadMessages()->first();

            // загрузить сообщения до первого непрочитанного (включительно) и после первого непрочитанного
            if ($oldestUnreadMessage) {
                $messages = SupportChatMessage::where('chat_id', $chat->id)
                    ->where('created_at', '<', $oldestUnreadMessage->created_at)
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc')
                    ->limit(3)
                    ->get()
                    ->reverse()
                    ->concat(
                        SupportChatMessage::where('chat_id', $chat->id)
                            ->where('created_at', '>=', $oldestUnreadMessage->created_at)
                            ->limit($limit)
                            ->get()
                    )
                    ->values();
            }
            // загрузить последние $limit сообщений
            else {
                $messages = SupportChatMessage::where('chat_id', $chat->id)
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc')
                    ->limit($limit)
                    ->reverse()
                    ->get()
                    ->values();
            }
        }

        $messagesCount = count($messages);
        $earliestLoadedMessage = $messagesCount > 0 ? $messages[0] : 0;
        $latestLoadedMessage = $messagesCount > 0 ? $messages[$messagesCount - 1] : 0;

        return response([
            'ok' => true,
            'data' => [
                'messages' => $messages,
                'earliest_loaded_id' => $earliestLoadedMessage->id,
                'latest_loaded_id' => $latestLoadedMessage->id
            ]
        ], 200);
    }

    public function writeMessage(SupportChatWriteMessageRequest $request)
    {
        $message = null;
        $chat = null;
        $user = auth()->user();

        if ($request->has('chat_id')) {
            $chat = SupportChat::find($request->chat_id);
            $message = SupportChatMessage::create([
                'chat_id' => $chat->id,
                'author_id' => $user->id,
                'sender_type' => SupportChatSenderTypeEnum::STAFF->value,
                'text' => $request->text,
            ]);
        } else {
            $chat = SupportChat::firstOrCreate([
                'user_id' => $user->id
            ], [
                'user_id' => $user->id,
                'status' => SupportChatStatusesEnum::OPEN->value
            ]);


            $message = SupportChatMessage::create([
                'chat_id' => $chat->id,
                'author_id' => $user->id,
                'sender_type' => SupportChatSenderTypeEnum::USER->value,
                'text' => $request->text,
            ]);
        }

        return response([
            'ok' => true,
            'data' => [
                'message' => $message,
                'latest_loaded_id' => $message->id
            ]
        ], 201);
    }

    public function getChatsList(SupportChatGetListRequest $request)
    {
        $chats = SupportChat::select([
            'support_chats.id',
            'support_chats.status',
            'support_chats.created_at',
            'support_chats.updated_at',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone_number as user_phone'
        ])
            ->with('latesetMessage:id,sender_type,text,support_chat_messages.chat_id,support_chat_messages.created_at')
            ->filter($request->filterableRequest)
            ->orderBy('status', 'asc')
            ->paginate();

        return response([
            'ok' => true,
            'data' => $chats
        ], 200);
    }

    public function markAsRead(SupportChatMarkAsReadRequest $request)
    {

    }

    public function changeStatus(SupportChatChangeStatusRequest $request)
    {
        $chat = SupportChat::find($request->chat_id);
        $chat->update([
            'status' => $request->status
        ]);

        return response([
            'ok' => true,
            'data' => [
                'chat' => $chat
            ]
        ], 201);
    }
}
