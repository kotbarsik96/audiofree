<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TaxonomiesController;
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
Route::get('product', [ProductsController::class, 'productPage']); // 2.2
Route::get('products/catalog/taxonomies', [TaxonomiesController::class, 'catalog']); // 2.3

Route::middleware('auth:sanctum')->group(function () {
  // 1. User
  Route::post('logout', [AuthController::class, 'logout']); // 1.3
  Route::get('profile/user', [AuthController::class, 'user']); // 1.4
  Route::post('profile/edit', [UsersController::class, 'edit']); // 1.5
  Route::post('profile/verify-email/request', [AuthController::class, 'requestVerifyEmail']); // 1.7.1
  Route::post('profile/verify-email', [AuthController::class, 'verifyEmail']); // 1.7.2
  Route::post('profile/change-email', [AuthController::class, 'changeEmail']); // 1.8
  Route::post('profile/change-password', [AuthController::class, 'changePassword']); // 1.9

  // 3. Product rating
  Route::post('product/rating', [ProductsController::class, 'setRating']); // 3.1
  Route::delete('product/rating', [ProductsController::class, 'removeRating']); // 3.2

  // 6. Product cart
  Route::post('product/cart', [CartController::class, 'store']); // 6.1
  Route::get('product/cart', [CartController::class, 'get']); // 6.2
  Route::delete('product/cart', [CartController::class, 'delete']); // 6.3
  Route::delete('product/cart/quantity', [CartController::class, 'minusOne']); // 6.4 

  // 7. Product favorites
  Route::post('product/favorites', [FavoritesController::class, 'store']); // 7.1
  Route::get('product/favorites', [FavoritesController::class, 'get']); // 7.2
  Route::delete('product/favorites', [FavoritesController::class, 'delete']); // 7.3

  // 8. Product order
  Route::post('product/order', [OrdersController::class, 'store']); // 8.1
  Route::get('product/order', [OrdersController::class, 'getSingle']); // 8.2
  Route::get('product/orders/products', [OrdersController::class, 'getProducts']); // 8.3
  Route::post('product/order/cancel', [OrdersController::class, 'cancel']); // 8.4 
});
