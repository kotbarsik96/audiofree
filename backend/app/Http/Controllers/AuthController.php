<?php

namespace App\Http\Controllers;

use App\Services\MessagesToUser\Mailable\VerifyEmailMailable;
use App\Models\User;
use App\Services\MessagesToUser\MTUController;
use Exception;
use Illuminate\Http\Request;
use App\Validations\AuthValidation;
use App\Http\Requests\SignupRequest;
use App\Services\MessagesToUser\Mailable\ResetPasswordMailable;
use App\Models\Confirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthController extends Controller
{
  public function __construct(protected $mtu = new MTUController())
  {
  }

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

    $user = User::getByEmail($email);
    throw_if(
      !$user,
      new NotFoundHttpException(__('abortions.userNotFound'))
    );

    $codeData = Confirmation::createCode($purpose, $email);
    $sentTo = $this->mtu->send(
      new ResetPasswordMailable($codeData->code, $user),
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

  public function verifyResetPasswordLink()
  {
    $purpose = 'prp_reset_password';
    $email = request('email');
    $code = request('code');

    $user = User::where('email', $email)->first();
    throw_if(
      !$user,
      new NotFoundHttpException(__('abortions.userNotFound'))
    );

    $codeValid = Confirmation::validateCode($purpose, $user->id, $code);
    throw_if(
      !$codeValid,
      new Exception(__('validation.incorrectCode'))
    );

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
    $purpose = 'prp_verify_email';
    $user = User::find(auth()->user()->id);

    throw_if(
      $user->email_verified_at,
      new BadRequestHttpException(__('abortions.emailAlreadyVerified'))
    );

    $codeData = Confirmation::createCode($purpose, $user);
    $sentTo = $this->mtu->send(new VerifyEmailMailable($codeData->code));
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
      new Exception(__('validation.incorrectLink'), 401)
    );

    $user = User::getByEmail($user->email);
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
