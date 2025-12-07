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
                'total_messages' => $chat->messages->count(),
                'first_message_id' => SupportChatMessage::where('chat_id', $chat->id)->first()?->id,
                'last_message_id' => SupportChatMessage::where('chat_id', $chat->id)->latest()?->first()?->id
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
            $messages = SupportChatMessage::where('chat_id', $chat->id)
                ->where('id', '<', $request->earliest_message_id)
                ->limit($limit)
                ->get();
        }
        // загрузить новые сообщения
        elseif ($request->has('latest_message_id')) {
            $messages = SupportChatMessage::where('chat_id', $chat->id)
                ->where('id', '>', $request->latest_message_id)
                ->limit($limit)
                ->get();
        } else {
            $oldestUnreadMessage = $chat->unreadMessages()->first();

            // загрузить сообщения до первого непрочитанного и после первого непрочитанного
            if ($oldestUnreadMessage) {
                $messages = SupportChatMessage::where('chat_id', $chat->id)
                    ->where('id', '<=', $oldestUnreadMessage->id)
                    ->limit(3)
                    ->get()
                    ->concat(
                        SupportChatMessage::where('chat_id', $chat->id)
                            ->where('id', '>', $oldestUnreadMessage->id)
                            ->limit($limit)
                            ->get()
                    );
            }
            // загрузить последние $limit сообщений
            else {
                $messages = SupportChatMessage::where('chat_id', $chat->id)
                    ->latest()
                    ->limit($limit)
                    ->get();
            }
        }

        return response([
            'ok' => true,
            'data' => [
                'messages' => $messages,
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
            'message' => $message
        ], 201);
    }

    public function getChatsList(SupportChatGetListRequest $request)
    {

    }

    public function markAsRead(SupportChatMarkAsReadRequest $request)
    {

    }
}
