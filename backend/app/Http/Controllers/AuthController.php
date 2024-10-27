<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use App\Validations\AuthValidation;
use App\Http\Requests\SignupRequest;
use App\Mail\ResetPassword;
use App\Models\EmailConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerifyEmail;

class AuthController extends Controller
{
  public function signup(SignupRequest $request)
  {
    $validated = $request->validated();

    User::create($validated);
    return $this->login($validated);
  }

  public function login($credentials = null)
  {
    if (!$credentials) $credentials = request(['email', 'password']);
    $user = User::getByEmail($credentials['email']);

    if (Hash::check($credentials['password'], $user->password)) {
      return [
        'ok' => true,
        'message' => __('general.helloUser', ['username' => $user->name]),
        'data' => [
          'token' => $user->createToken(time())->plainTextToken,
        ],
      ];
    }

    return response(['ok' => false, 'message' => __('validation.incorrectLoginOrPassword')], 401);
  }

  public function user()
  {
    return response()->json([
      'data' => auth()->user()
    ]);
  }

  public function logout()
  {
    request()->user()->currentAccessToken()->delete();

    return response([
      'ok' => true,
      'message' => __('general.loggedOut')
    ], 200);
  }

  public function requestResetPassword()
  {
    $purpose = 'reset_password';
    $email = request('email');

    $user = User::getByEmail($email);
    if (!$user)
      abort(404, __('abortions.userNotFound'));

    EmailConfirmation::sendEmail($purpose, $email, ResetPassword::class);

    return response([
      'ok' => true,
      'message' => __('general.emailSent')
    ]);
  }

  public function verifyResetPasswordLink()
  {
    $purpose = 'reset_password';
    $email = request('email');
    $code = request('code');

    EmailConfirmation::verifyLink($purpose, $email, $code);

    return response([
      'ok' => true,
    ]);
  }

  public function resetPassword(Request $request)
  {
    $email = request('email');
    $code = request('code');
    $validated = $request->validate([
      'password' => AuthValidation::password()
    ]);

    User::resetPassword($email, $code, $validated['password']);

    return [
      'ok' => true,
      'message' => __('general.passwordChanged')
    ];
  }

  public function requestVerifyEmail()
  {
    $purpose = 'verify_email';
    $user = auth()->user();
    if (!$user)
      abort(404, __('abortions.userNotFound'));

    if ($user->email_verified_at)
      abort(401, __('abortions.emailAlreadyVerified'));

    $email = $user->email;

    EmailConfirmation::sendEmail($purpose, $email, VerifyEmail::class);

    return response([
      'ok' => true,
      'message' => __('general.emailSent')
    ]);
  }

  public function verifyEmail()
  {
    $purpose = 'verify_email';
    $code = request('code');

    $user = auth()->user();
    if (!$user)
      abort(404, __('abortions.userNotFound'));

    $email = $user->email;

    EmailConfirmation::verifyLink($purpose, $email, $code);

    $user = User::getByEmail($email);
    $user->update([
      'email_verified_at' => Carbon::now()
    ]);
    EmailConfirmation::deleteForPurpose($user, $purpose);

    return response([
      'ok' => true,
      'message' => __('general.emailVerified')
    ]);
  }

  public function changeEmail(Request $request)
  {
    $validated = $request->validate([
      'email' => AuthValidation::email()
    ]);

    $changed = User::changeEmail($validated['email']);
    if ($changed['wasVerified'])
      $this->requestVerifyEmail();

    return response([
      'message' => $changed['wasVerified']
        ? __('general.emailAddressChangedCodeSent')
        : __('general.emailAddressChanged')
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
