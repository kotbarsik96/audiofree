<?php

namespace App\Http\Controllers;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Events\SupportChat\SupportChatChangeInfoEvent;
use App\Events\SupportChat\SupportChatReadEvent;
use App\Http\Requests\SupportChat\SupportChatGetListRequest;
use App\Http\Requests\SupportChat\SupportChatGetMessagesRequest;
use App\Http\Requests\SupportChat\SupportChatInfoRequest;
use App\Http\Requests\SupportChat\SupportChatMarkAsReadRequest;
use App\Http\Requests\SupportChat\SupportChatUpdateWritingStatusRequest;
use App\Http\Requests\SupportChat\SupportChatWriteMessageRequest;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use App\Models\SupportChat\SupportChatWritingStatus;
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
            'data' => $chat->getInfo($request->getCurrentSenderType()),
            'ok' => true,
        ]);
    }

    public function getMessages(SupportChatGetMessagesRequest $request)
    {
        $chat = null;
        $messages = [];
        $limit = 30;

        if ($request->has('chat_id')) {
            $chat = SupportChat::find($request->chat_id);
        } else {
            $chat = auth()->user()->supportChat;
            throw_if(!$chat, new NotFoundHttpException(__('abortions.chatNotFound')));
        }

        // загрузить предыдущие сообщения (прокрутка чата вверх)
        if ($request->has('earliest_message_id')) {
            $earliestMessage = SupportChatMessage::find($request->earliest_message_id);

            if ($earliestMessage) {
                $builder = SupportChatMessage::where('chat_id', $chat->id)
                    ->where('created_at', '<', $earliestMessage->created_at)
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc');
                if (!$request->has('load_all'))
                    $builder->limit($limit);
                $messages = $builder
                    ->get()
                    ->reverse()
                    ->values();
            }
        }
        // загрузить новые сообщения (прокрутка чата вниз)
        elseif ($request->has('latest_message_id')) {
            $latestMessage = SupportChatMessage::find($request->latest_message_id);

            if ($latestMessage) {
                $builder = SupportChatMessage::where('chat_id', $chat->id)
                    ->where('created_at', '>', $latestMessage->created_at);

                // если нужно загрузить все оставшиеся - не выставлять лимит
                if (!$request->has('load_all'))
                    $builder->limit($limit);

                $messages = $builder
                    ->get()
                    ->values();
            }
        }
        // первая загрузка (открытие чата)
        else {
            $oldestUnreadMessage = $chat->unreadMessagesFromCompanion($request->getCurrentSenderType())->first();

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
                    ->get()
                    ->reverse()
                    ->values();
            }
        }

        $messagesCount = count($messages);
        $earliestLoadedMessage = $messagesCount > 0 ? $messages[0] : null;
        $latestLoadedMessage = $messagesCount > 0 ? $messages[$messagesCount - 1] : null;

        return response([
            'ok' => true,
            'data' => [
                'messages' => $messages,
                'earliest_loaded_id' => $earliestLoadedMessage?->id,
                'latest_loaded_id' => $latestLoadedMessage?->id
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
            $chat->setOpenStatus();
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

            $chat->setOpenStatus();

            $message = SupportChatMessage::create([
                'chat_id' => $chat->id,
                'author_id' => $user->id,
                'sender_type' => SupportChatSenderTypeEnum::USER->value,
                'text' => $request->text,
            ]);
        }

        $updatedIds = $chat->unreadMessagesFromCompanion($request->getCurrentSenderType())
            ->select('id')
            ->get()
            ->pluck('id');
        $chat->unreadMessagesFromCompanion($request->getCurrentSenderType())
            ->update([
                'read_at' => Carbon::now()
            ]);

        SupportChatReadEvent::dispatch($updatedIds, $chat, auth()->user());

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
        $chats = SupportChat::chatsList()
            ->filter($request->filterableRequest)
            ->paginate($request->per_page);

        return response([
            'ok' => true,
            'data' => $chats
        ], 200);
    }

    public function markAsRead(SupportChatMarkAsReadRequest $request)
    {
        $builder = $request->chat->unreadMessagesFromCompanion($request->getCurrentSenderType())
            ->where('created_at', '>=', $request->firstReadMessage->created_at)
            ->limit($request->read_count);
        $updatedIds = $builder->clone()->select('id')->get()->pluck('id');
        $updated = $builder->update([
            'read_at' => Carbon::now()
        ]);

        SupportChatReadEvent::dispatch($updatedIds, $request->chat, auth()->user());

        return response([
            'ok' => true,
            'data' => [
                'read_count' => $updated,
                'chat_info' => $request->chat->getInfo($request->getCurrentSenderType())
            ],
        ], 201);
    }

    public function changeStatus(SupportChatChangeStatusRequest $request)
    {
        $chat = SupportChat::find($request->chat_id);
        $chat->update([
            'status' => $request->status
        ]);

        SupportChatChangeInfoEvent::dispatch($chat);

        return response([
            'ok' => true,
            'data' => [
                'chat' => $chat->getInfo(SupportChatSenderTypeEnum::STAFF)
            ]
        ], 201);
    }

    public function updateWritingStatus(SupportChatUpdateWritingStatusRequest $request)
    {
        $user = auth()->user();
        $chat = $request->has('chat_id')
            ? SupportChat::find($request->chat_id)
            : $user->supportChat;

        $status = SupportChatWritingStatus::firstOrCreate([
            'chat_id' => $chat->id,
            'writer_id' => $user->id
        ]);
        // запускает SupportChatWriteStatusEvent::dispatch
        $status->update([
            'started_writing_at' => $request->is_writing ? Carbon::now() : null
        ]);

        return [
            'ok' => true
        ];
    }
}
