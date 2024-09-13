<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Validations\AuthValidation;

class UsersController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function edit(Request $request)
  {
    $validated = $request->validate([
      'name' => AuthValidation::userField(),
      'surname' => AuthValidation::userField(),
      'patronymic' => AuthValidation::userField(),
      'phone_number' => AuthValidation::phoneNumber(),
      'location' => AuthValidation::userField(),
      'street' => AuthValidation::userField(),
      'house' => AuthValidation::userField()
    ]);


    if (!$user = auth()->user()) {
      return response([
        'message' => __('general.authFailed')
      ], 401);
    }

    $user = User::find($user->id);
    $user->update($validated);

    return response()->json([
      'message' => __('general.dataUpdated'),
      'ok' => true,
      'data' => [
        'user' => $user
      ]
    ]);
  }
}
