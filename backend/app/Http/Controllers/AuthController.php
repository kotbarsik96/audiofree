<?php

namespace App\Http\Controllers;

use App\Services\MessagesToUser\Mailable\VerifyEmailMailable;
use App\Models\User;
use App\Services\MessagesToUser\MTUController;
use Illuminate\Http\Request;
use App\Validations\AuthValidation;
use App\Http\Requests\SignupRequest;
use App\Services\MessagesToUser\Mailable\ResetPasswordMailable;
use App\Models\Confirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
    if (!$credentials)
      $credentials = request(['email', 'password']);
    $user = User::where('email', $credentials['email'])->first();

    if ($user && Hash::check($credentials['password'], $user->password)) {
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
    $purpose = 'prp_reset_password';
    $email = request('email');

    $user = User::getBy('email', $email);

    Confirmation::checkIfValidCodeExists($purpose, $user->id, true);

    $codeData = Confirmation::createCode($purpose, $user);
    $mtu = new MTUController($user);
    $sentTo = $mtu->send(
      new ResetPasswordMailable($codeData->unhashedCode, $user),
      // добавить Telegramable
    );
    $codeData->update([
      'sent_to' => $sentTo
    ]);

    return response([
      'ok' => true,
      'message' => __(
        'general.codeSentTo',
        ['sentTo' => implode(', ', $sentTo)]
      )
    ]);
  }

  public function throwErrorIfResetPasswordCodeInvalid(): bool
  {
    $purpose = 'prp_reset_password';
    $email = request('email');
    $code = request('code');

    $user = User::where('email', $email)->first();
    throw_if(
      !$user,
      new UnauthorizedHttpException('', __('abortions.userNotFound'))
    );

    $codeValid = Confirmation::validateCode($purpose, $user->id, $code);
    throw_if(
      !$codeValid,
      new UnauthorizedHttpException('', __('validation.incorrectCode'))
    );

    return true;
  }

  public function verifyResetPasswordLink()
  {
    $this->throwErrorIfResetPasswordCodeInvalid();

    return response([
      'ok' => true,
    ]);
  }

  public function resetPassword(Request $request)
  {
    $purpose = 'prp_reset_password';
    $this->throwErrorIfResetPasswordCodeInvalid();

    $user = User::getBy('email', request('email'));

    $validated = $request->validate([
      'password' => AuthValidation::password()
    ]);

    $user->updatePassword($validated['password']);
    Confirmation::deleteForPurpose($user, $purpose);

    return response([
      'ok' => true,
      'message' => __('general.passwordChanged')
    ]);
  }

  public function requestVerifyEmail()
  {
    $purpose = 'prp_verify_email';
    $user = User::find(auth()->user()->id);

    throw_if(
      $user->email_verified_at,
      new BadRequestHttpException(__('abortions.emailAlreadyVerified'))
    );

    Confirmation::checkIfValidCodeExists($purpose, $user->id, true);

    $codeData = Confirmation::createCode($purpose, $user);
    $mtu = new MTUController($user);
    $sentTo = $mtu->send(new VerifyEmailMailable($codeData->unhashedCode));
    $codeData->update([
      'sent_to' => $sentTo
    ]);

    return response([
      'ok' => true,
      'message' => __('general.emailSent')
    ]);
  }

  public function verifyEmail()
  {
    $purpose = 'prp_verify_email';
    $code = request('code');

    $user = auth()->user();
    throw_if(
      !Confirmation::validateCode($purpose, $user->id, $code),
      new UnauthorizedHttpException('', __('validation.incorrectLink'))
    );

    $user = User::getBy('email', $user->email);
    $user->update([
      'email_verified_at' => Carbon::now()
    ]);
    Confirmation::deleteForPurpose($user, $purpose);

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
