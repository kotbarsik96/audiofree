<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TaxonomiesController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// 1. User
Route::post('signup', [AuthController::class, 'signup']); // 1.1
Route::post('login', [AuthController::class, 'login']); // 1.2
Route::post('profile/reset-password/request', [AuthController::class, 'requestResetPassword']); // 1.6.1
Route::post(
  'profile/reset-password/verify-link',
  [AuthController::class, 'verifyResetPasswordLink']
); // 1.6.2
Route::post('profile/reset-password/new-password', [AuthController::class, 'resetPassword']); // 1.6.3

// 2. Catalog
Route::get('products/catalog', [ProductsController::class, 'catalog']); // 2.1
// 2.2 внизу
Route::get('products/catalog/filters', [TaxonomiesController::class, 'catalogFilters']); // 2.3
Route::get('products/catalog/sorts', [TaxonomiesController::class, 'catalogSorts']); // 2.4
Route::get('products/{productId}/reviews', [ProductsController::class, 'reviews']); // 2.5

Route::middleware('auth:sanctum')->group(function () {
  // 1. User
  Route::post('logout', [AuthController::class, 'logout']); // 1.3
  Route::get('profile/user', [AuthController::class, 'user']); // 1.4
  Route::post('profile/edit', [UsersController::class, 'edit']); // 1.5
  Route::post('profile/verify-email/request', [AuthController::class, 'requestVerifyEmail']); // 1.7.1
  Route::post('profile/verify-email', [AuthController::class, 'verifyEmail']); // 1.7.2
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

  // 8. Product order
  // Route::post('product/order', [OrdersController::class, 'store']); // 8.1
  // Route::get('product/order', [OrdersController::class, 'getSingle']); // 8.2
  // Route::get('product/orders/products', [OrdersController::class, 'getProducts']); // 8.3
  // Route::post('product/order/cancel', [OrdersController::class, 'cancel']); // 8.4 

  // 99. Test
  Route::post('test/image', [TestController::class, 'uploadImage']);
});

// 2. Catalog
Route::get('product/{productId}/{variationId}', [ProductsController::class, 'productPage']); // 2.2