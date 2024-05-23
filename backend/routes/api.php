<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

App::setLocale(request()->header('Locale'));

// 1. User
Route::post('signup', [AuthController::class, 'signup']); // 1.1
Route::post('login', [AuthController::class, 'login']); // 1.2
Route::get('profile/reset-password', [AuthController::class, 'getResetPassword']); // 1.7.1
Route::post('profile/reset-password', [AuthController::class, 'resetPasswordVerify']); // 1.7.2

// 2. Product
Route::get('product/catalog', [ProductsController::class, 'catalog']); // 2.9

Route::middleware('auth:sanctum')->group(function () {
  // 1. User
  Route::post('logout', [AuthController::class, 'logout']); // 1.3
  Route::get('profile/user', [AuthController::class, 'user']); // 1.4
  Route::post('profile/edit', [UsersController::class, 'edit']); // 1.5
  Route::get('profile/verify-email', [AuthController::class, 'getEmailVerifyCode']); // 1.6.1
  Route::post('profile/verify-email', [AuthController::class, 'emailVerifyCode']); // 1.6.2
  Route::post('profile/change-email', [AuthController::class, 'changeEmail']); // 1.8
  Route::post('profile/change-password', [AuthController::class, 'changePassword']); // 1.9

  // 2. Product
  Route::post('product', [ProductsController::class, 'store']); // 2.1
  Route::post('product/update', [ProductsController::class, 'update']); // 2.2
  Route::delete('product', [ProductsController::class, 'delete']); // 2.3
  Route::post('product/variation', [ProductsController::class, 'storeVariations']); // 2.4
  Route::post('product/gallery', [ProductsController::class, 'uploadGallery']); // 2.5
  Route::post('product/info', [ProductsController::class, 'storeInfo']); // 2.6
  Route::post('product/rating', [ProductsController::class, 'setRating']); // 2.7
  Route::delete('product/rating', [ProductsController::class, 'removeRating']); // 2.8

  // 3. Image
  Route::post('image', [ImagesController::class, 'upload']);
  Route::delete('image', [ImagesController::class, 'delete']);
});
