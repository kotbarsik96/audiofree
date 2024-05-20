<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use App\Validations\AuthValidation;
use App\Http\Requests\SignupRequest;
use App\Models\EmailConfirmation;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
  public function signup(SignupRequest $request)
  {
    $validated = array_merge($request->validated(), [
      'role' => 'USER'
    ]);

    User::create($validated);
    return $this->login($validated);
  }

  public function login($credentials = null)
  {
    if (!$credentials) $credentials = request(['email', 'password']);
    $user = User::getByEmail($credentials['email']);

    if (Hash::check($credentials['password'], $user->password)) {
      return [
        'token' => $user->createToken(time())->plainTextToken
      ];
    }

    return response(['ok' => false], 401);
  }

  public function user()
  {
    return response()->json(auth()->user());
  }

  public function logout()
  {
    request()->user()->currentAccessToken()->delete();

    return response([
      'ok' => true,
      'message' => __('general.loggedOut')
    ], 200);
  }

  public function getEmailVerifyCode()
  {
    User::sendEmailVerifyCode();

    return response([
      'ok' => true,
      'message' => __('general.codeSentToEmail')
    ]);
  }

  public function emailVerifyCode()
  {
    $user = User::verifyEmail();

    return response([
      'ok' => true,
      'message' => __('general.emailVerified', ['email' => $user->email])
    ], 200);
  }

  public function getResetPassword()
  {
    User::sendResetPasswordLink();

    return response([
      'ok' => true,
      'message' => __('general.linkSentToEmail')
    ]);
  }

  public function resetPasswordVerify(Request $request)
  {
    $validated = $request->validate([
      'password' => AuthValidation::password()
    ]);

    User::verifyResetPasswordCode($validated['password'], request('code'));

    return response([
      'ok' => true,
      'message' => __('general.passwordChanged')
    ]);
  }

  public function changeEmail(Request $request)
  {
    $validated = $request->validate([
      'email' => AuthValidation::email()
    ]);

    try {
      EmailConfirmation::checkIfValidCodeExists('verify_email', auth()->user()->id);
    } catch (Exception $err) {
      abort(401, 'На текущий привязанный email недавно уже был запрошен код подтверждения');
    }

    User::changeEmail($validated['email']);

    return response([
      'message' => __('general.emailAddressChanged')
    ]);
  }

  public function changePassword(Request $request)
  {
    $validated = $request->validate([
      'password' => AuthValidation::password()
    ]);
    User::changePassword($validated['password']);

    return response([
      'message' => __('general.passwordChanged')
    ]);
  }
}
