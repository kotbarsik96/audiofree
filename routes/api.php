<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SupportChatController;
use App\Http\Controllers\TaxonomiesController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// 1. User
Route::post('signup', action: [AuthController::class, 'signup']); // 1.1
Route::post('login', [AuthController::class, 'login']); // 1.2
Route::post('logout', [AuthController::class, 'logout'])
  ->middleware('auth:web'); // 1.3
Route::post(
  'profile/reset-password/request',
  [AuthController::class, 'requestResetPassword']
); // 1.6.1
Route::post(
  'profile/reset-password/verify-link',
  [AuthController::class, 'verifyResetPasswordLink']
); // 1.6.2
Route::post(
  'profile/reset-password/new-password',
  [AuthController::class, 'resetPassword']
); // 1.6.3
Route::post(
  'profile/request-login',
  [AuthController::class, 'requestLogin']
); // 1.11

// 2. Catalog
Route::get('products/catalog', [ProductsController::class, 'catalog']); // 2.1
// 2.2 внизу
Route::get('products/catalog/filters', [TaxonomiesController::class, 'catalogFilters']); // 2.3
Route::get('products/catalog/sorts', [TaxonomiesController::class, 'catalogSorts']); // 2.4
Route::get('products/{productId}/reviews', [ProductsController::class, 'reviews']); // 2.5

// 7. SEO
Route::get('page/{slug}', [SeoController::class, 'getPageInfo']); // 7.1

// 8. Search
Route::get('search/products', [SearchController::class, 'products']); // 8.2

Route::middleware('auth:sanctum')->group(function () {
  // 1. User
  Route::get('profile/user', [AuthController::class, 'user']); // 1.4
  Route::post('profile/edit', [UsersController::class, 'edit']); // 1.5
  Route::post(
    'profile/verification/request',
    [AuthController::class, 'requestVerification']
  ); // 1.7.1
  Route::post(
    'profile/verification/confirm',
    [AuthController::class, 'confirmVerification']
  ); // 1.7.2
  Route::post('profile/change-email', [AuthController::class, 'changeEmail']); // 1.8
  Route::post('profile/change-password', [AuthController::class, 'changePassword']); // 1.9
  Route::get('profile/products/collections', [UsersController::class, 'getProductsCollections']); // 1.10

  // 2. Catalog
  Route::get('/product/{productId}/user-review', [ProductsController::class, 'userReview']); // 2.6

  // 3. Product rating
  Route::post('product/rating', [ProductsController::class, 'setRating']); // 3.1
  Route::delete('product/rating', [ProductsController::class, 'removeRating']); // 3.2

  // 4. Product cart
  Route::post('product/cart', [CartController::class, 'store']); // 4.1
  Route::get('product/cart', [CartController::class, 'get']); // 4.2
  Route::delete('product/cart/item', [CartController::class, 'delete']); // 4.3

  // 5. Product favorites
  Route::post('product/favorites', [FavoritesController::class, 'store']); // 5.1
  Route::get('product/favorites', [FavoritesController::class, 'get']); // 5.2
  Route::delete('product/favorites', [FavoritesController::class, 'delete']); // 5.3
  Route::get('product/favorites/sorts', [TaxonomiesController::class, 'favoritesSorts']); // 5.4

  // 6. Product order
  Route::post('order/new-attempt', [OrdersController::class, 'creationAttempt']); // 6.1
  Route::post('order/new', [OrdersController::class, 'create']); // 6.2
  Route::delete('order/cancel/{order_id}', [OrdersController::class, 'cancel']); // 6.3
  Route::get('order/list', [OrdersController::class, 'getOrdersList']); // 6.4
  Route::get('order/single/{order_id}', [OrdersController::class, 'getOrder']); // 6.5
  Route::get(
    'order/creation-data',
    [OrdersController::class, 'getOrderCreationData']
  ); // 6.6
  Route::get('order/sorts', [TaxonomiesController::class, 'orderSorts']); // 6.7

  // 8. Search
  Route::get('search/address', [SearchController::class, 'address'])
    ->middleware(['throttle:search-address']); // 8.1

  // 9. Chats
  Route::get('support-chat/user/history', [SupportChatController::class, 'userGetMessages']); // 9.1
  Route::post('support-chat/user/message', [SupportChatController::class, 'userWriteMessage']); // 9.2
  Route::get('support-chat/supporter/history', [
    SupportChatController::class,
    'supporterGetMessages'
  ]); // 9.3
  Route::post('support-chat/supporter/message', [
    SupportChatController::class,
    'supporterWriteMessage'
  ]); // 9.4
  Route::get('support-chat/chats-list', [SupportChatController::class, 'supporterGetChatsList']); // 9.5
  Route::get('support-chat/chat-info', [SupportChatController::class, 'chatInfo']); // 9.6
  Route::post('support-chat/read', [SupportChatController::class, 'read']); // 9.7

  // 99. Test
  Route::post('test', [TestController::class, 'test']);
});

// 2. Catalog
Route::get('product/{productSlug}/{variationSlug}', [ProductsController::class, 'productPage']); // 2.2