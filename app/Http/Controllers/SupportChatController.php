<?php

namespace App\Http\Controllers;

use App\Enums\SupportChat\SupportChatSenderTypeEnum;
use App\Enums\SupportChat\SupportChatStatusesEnum;
use App\Http\Requests\SupportChat\SupportChatGetListRequest;
use App\Http\Requests\SupportChat\SupportChatGetMessagesRequest;
use App\Http\Requests\SupportChat\SupportChatInfoRequest;
use App\Http\Requests\SupportChat\SupportChatMarkAsReadRequest;
use App\Http\Requests\SupportChat\SupportChatUpdateWritingStatusRequest;
use App\Http\Requests\SupportChat\SupportChatWriteMessageRequest;
use App\Http\Resources\SupportChat\SupportChatInfoResource;
use App\Http\Resources\SupportChat\SupportChatMessageResource;
use App\Models\SupportChat\SupportChat;
use App\Models\SupportChat\SupportChatMessage;
use App\Services\SupportChat\SupportChatMessagesService;
use App\Services\SupportChat\SupportChatService;
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
            'data' => (new SupportChatInfoResource($chat))->setSenderType($request->getCurrentSenderType()),
            'ok' => true,
        ]);
    }

    public function getMessages(SupportChatGetMessagesRequest $request, SupportChatMessagesService $service)
    {
        $chat = null;

        if ($request->has('chat_id')) {
            $chat = SupportChat::find($request->chat_id);
        } else {
            $chat = auth()->user()->supportChat;
            throw_if(!$chat, new NotFoundHttpException(__('abortions.chatNotFound')));
        }

        $service->collectMessages(
            $chat,
            $request->getCurrentSenderType(),
            $request->earliest_message_id,
            $request->latest_message_id,
            $request->load_all ?? false
        );

        return response([
            'ok' => true,
            'data' => [
                'messages' => $service->messages,
                'earliest_loaded_id' => $service->earliest_message_id,
                'latest_loaded_id' => $service->latest_message_id
            ]
        ], 200);
    }

    public function writeMessage(SupportChatWriteMessageRequest $request, SupportChatService $service)
    {
        $chat = null;
        $user = auth()->user();

        if ($request->has('chat_id')) {
            $chat = SupportChat::find($request->chat_id);
        } else {
            $chat = SupportChat::firstOrCreate([
                'user_id' => $user->id
            ], [
                'user_id' => $user->id,
                'status' => SupportChatStatusesEnum::OPEN->value
            ]);
        }

        $message = $service->writeMessage($chat, $request->getCurrentSenderType(), $request->text);

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
        $chats = SupportChat::chatsList(auth()->user()->id)
            ->filter($request->filterableRequest)
            ->paginate($request->per_page)
            ->through(function ($item) {
                $msg = $item->latest_message;
                if ($msg) {
                    unset($item->latest_message);
                    $item->latest_message = (new SupportChatMessageResource($msg))
                        ->setSenderType(SupportChatSenderTypeEnum::STAFF);
                }
                return $item;
            });

        return response([
            'ok' => true,
            'data' => $chats
        ], 200);
    }

    public function markAsRead(SupportChatMarkAsReadRequest $request, SupportChatService $service)
    {
        $updated = $service->markMessagesAsRead(
            $request->chat,
            $request->firstReadMessage,
            $request->read_count,
            $request->getCurrentSenderType()
        );

        return response([
            'ok' => true,
            'data' => [
                'read_count' => $updated,
                'chat_info' => (new SupportChatInfoResource($request->chat))->setSenderType($request->getCurrentSenderType())
            ],
        ], 201);
    }

    public function changeStatus(SupportChatChangeStatusRequest $request, SupportChatService $service)
    {
        $chat = SupportChat::find($request->chat_id);

        $changed = $service->changeChatStatus($chat, SupportChatStatusesEnum::fromValue($request->status));

        return response([
            'ok' => true,
            'data' => [
                'chat' => (new SupportChatInfoResource($chat))->setSenderType(SupportChatSenderTypeEnum::STAFF),
                'changed' => $changed,
            ]
        ], 201);
    }

    public function updateWritingStatus(SupportChatUpdateWritingStatusRequest $request, SupportChatService $service)
    {
        $chat = $request->has('chat_id')
            ? SupportChat::find($request->chat_id)
            : auth()->user()->supportChat;

        if ($chat)
            $service->updateWritingStatus($chat, $request->is_writing);

        return [
            'ok' => true
        ];
    }
}
