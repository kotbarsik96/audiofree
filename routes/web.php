<?php

use App\DTO\Enums\ProductFilterEnum;
use App\DTO\ProductFilterCheckboxDTO;
use App\DTO\ProductFilterInfoDTO;
use App\DTO\ProductFilterRangeDTO;
use App\Models\SupportChat\SupportChatMessage;
use App\Services\Image\ImageService;
use App\Services\Search\SearchProduct\SearchProductResult;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

if (env('DEV_MODE') && env('DEV_MODE') !== 'false') {
    Route::get('/test', function (Request $request) {
        $user = auth()->user();

        $supportChat = $user->supportChat;
        $perPage = intval($request->get('per_page') ?? 10);

        $firstUnreadMessage = SupportChatMessage::unreadMessages($supportChat)
            ->orderBy('created_at')
            ->first();

        return SupportChatMessage::where('created_at', '<', $firstUnreadMessage->created_at)
            ->where('chat_id', $supportChat->id)->count();
    });
}

require __DIR__.'/auth.php';
