<?php

namespace App\Http\Controllers;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Events\SupportChat\SupportChatReadEvent;
use App\Events\SupportChat\SupportChatWriteStatusEvent;
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
        $limit = 15;

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

                // если нужно загрузить все оставшиеся - отметить все сообщения прочитанными и не выставлять лимит
                if ($request->has('load_all')) {
                    $chat->unreadMessagesFromCompanion($request->getCurrentSenderType())
                        ->update([
                            'read_at' => Carbon::now()
                        ]);
                } else
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
            'users.phone_number as user_phone',
            'users.telegram as user_telegram'
        ])
            ->addSelect([
                'latest_message' => SupportChatMessage::select('text')
                    ->whereColumn('support_chat_messages.chat_id', 'support_chats.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1),
                'latest_message_created_at' => SupportChatMessage::select('created_at')
                    ->whereColumn('support_chat_messages.chat_id', 'support_chats.id')
                    ->orderBy('created_at', 'desc')
                    ->limit(1),
                'writers_count' => SupportChatWritingStatus::selectRaw('count(*)')
                    ->whereNotNull('support_chat_writing_statuses.started_writing_at')
                    ->whereColumn('support_chat_writing_statuses.chat_id', 'support_chats.id')
                    ->where('support_chat_writing_statuses.writer_id', '!=', auth()->user()->id)
            ])
            ->filter($request->filterableRequest)
            ->orderBy('latest_message_created_at', 'desc')
            ->orderBy('status', 'asc')
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
        $updatedIds = $builder->clone()->select('id')->get()->pluck('id')->toArray();
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

        return response([
            'ok' => true,
            'data' => [
                'chat' => $chat
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
