<?php

namespace App\Http\Controllers;

use App\DTO\Auth\AuthDTO;
use App\DTO\Auth\AuthDTOCollection;
use App\DTO\ConfirmationPurpose\ConfirmationPurposeDTOCollection;
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
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class AuthController extends Controller
{
  public function signup(SignupRequest $request)
  {
    $response = null;
    $validated = $request->validated();

    $user = User::create($validated);

    $codeSentTo = null;
    // пользователь, указавший пароль, логинится сразу
    if (array_key_exists('password', $validated) && $validated['password']) {
      $response = $this->successfullLogin($user);
    }
    // пользователь, указавший только логин, получит код авторизации
    else {
      // найти один из указанных ресурсов в логине и выслать код туда
      foreach (AuthDTOCollection::getAllDTOs() as $dto) {
        // если код уже был выслан - выйти из цикла
        if ($codeSentTo)
          break;

        // выслать код авторизации на один из указанных пользователем ресурсов
        $hasLogin = array_key_exists(
          $dto->columnName,
          $validated
        ) && $validated[$dto->columnName];
        if ($hasLogin) {
          $codeSentTo = $this->sendLoginCode(
            $user,
            $dto->loginAble
          );
        }
      }

      if ($codeSentTo) {
        $response = response([
          'ok' => true,
          'message' => __(
            'general.registeredAndCodeSentTo',
            ['sentTo' => $codeSentTo]
          )
        ]);
      }
    }

    return $response ?? response([
      'ok' => false,
      'message' => __('validation.login.required')
    ], 422);
  }

  /**
   * Находит пользователя и пытается залогинить его по паролю или коду авторизации
   */
  public function login(Request $request)
  {
    $response = null;

    // получить пользователя или выдать ошибку
    $user = User::getByLogin($request->login);

    if ($request->password) {
      $response = $this->attemptLoginByPassword($user, $request->password);
    }
    if (!$response && $request->code) {
      $response = $this->attemptLoginByCode($user, $request->code, $request->login);
    }

    return $response ?: response(
      [
        'ok' => false,
        'message' => __('validation.incorrectLoginOrPassword')
      ],
      401
    );
  }

  /**
   * Пытается залогинить пользователя по паролю
   */
  public function attemptLoginByPassword(User $user, string $password)
  {
    if ($user->password && Hash::check($password, $user->password)) {
      return $this->successfullLogin($user);
    }
    return false;
  }

  /**
   * Пытается залогинить пользователя по коду
   * 
   * Если логин по коду успешно прошёл - поставить <columnName>_verified_at
   */
  public function attemptLoginByCode(User $user, string $code, string $login)
  {
    $purpose = 'prp_login';

    $codeValid = Confirmation::validateCode($purpose, $user->id, $code);
    if ($codeValid) {
      // выставить подтверждение логина
      $dto = AuthDTOCollection::getDTOByLogin($user, $login);
      $user->update([
        $dto->verifiedColumName => Carbon::now()
      ]);
      Confirmation::deleteForPurpose($user, $purpose);
      return $this->successfullLogin($user);
    }
    return false;
  }

  /**
   * Логинит пользователя и генерирует ответ с токеном. Не занимается проверкой данных, сразу делает логин
   */
  public function successfullLogin(User $user)
  {
    return response([
      'ok' => true,
      'message' => __('general.helloUser', ['username' => $user->name]),
      'data' => [
        'token' => $user->createToken(time())->plainTextToken,
      ],
    ]);
  }

  /**
   * Пользователь вводит логин и, если есть пароль - отправляется ответ об этом
   * 
   * Если пароля нет, либо в request есть поле code_required, вышлет код по логину
   */
  public function requestLogin(Request $request)
  {
    $user = User::getByLogin($request->login);

    $response = null;
    if ($user->password && !$request->code_required) {
      $response = response([
        'ok' => true,
        'message' => __('general.enterPassword'),
        'data' => [
          'has_password' => true
        ]
      ]);
    } else {
      Confirmation::checkIfValidCodeExists('prp_login', $user->id, true);

      $dto = AuthDTOCollection::getDTOByLogin($user, $request->login);
      $sentTo = $this->sendLoginCode($user, $dto->loginAble);
      $response = response([
        'ok' => true,
        'message' => __('general.codeSentTo', ['sentTo' => $sentTo]),
        'data' => [
          'has_code' => true
        ]
      ]);
    }

    return $response;
  }

  /**
   * Высылает код авторизации на запрошенный ресурс
   * @param \App\Models\User $user пользователь
   * @param string $able LoginMailable или подобный, принимающий код в конструктор
   * @return $sentTo ресурс, на который выслан код
   */
  public function sendLoginCode(User $user, string $able): string
  {
    $mtu = new MTUController($user);

    $purpose = 'prp_login';
    $dto = ConfirmationPurposeDTOCollection::getDTO($purpose);

    Confirmation::checkIfValidCodeExists(
      $purpose,
      $user->id,
      true
    );

    $codeData = Confirmation::createCode($purpose, $user, $dto->codeLength);
    $sentTo = $mtu->send(new $able($codeData->unhashedCode, $user));
    $codeData->update(['sent_to' => $sentTo]);

    return $sentTo[0];
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

  public function getAuthDTO($entity): AuthDTO
  {
    $dto = AuthDTOCollection::getDTO($entity);
    throw_if(
      !$dto,
      new UnprocessableEntityHttpException(__('abortions.noVerificationEntity'))
    );

    return $dto;
  }

  /**
   * Запросить подтверждение ресурса, зарегистрированного в AuthDTOCollection
   */
  public function requestVerification(Request $request)
  {
    $dto = $this->getAuthDTO($request->entity);

    $purpose = "prp_verify_$request->entity";
    $verifiedColumnName = $dto->verifiedColumName;
    $user = User::find(auth()->user()->id);

    /** выбросить ошибку, если у пользователя уже подтверждено поле */
    throw_if(
      $user->$verifiedColumnName,
      new BadRequestHttpException(__('abortions.verificationEntityVerified'))
    );

    Confirmation::checkIfValidCodeExists($purpose, $user->id, true);

    $codeData = Confirmation::createCode($purpose, $user);
    $mtu = new MTUController($user);
    $sentTo = $mtu->send(new $dto->verifyAble($codeData->unhashedCode));
    $codeData->update([
      'sent_to' => $sentTo
    ]);

    return response([
      'ok' => true,
      'message' => __(
        'general.codeSentTo', 
        ['sentTo' => implode(', ', $sentTo)])
    ]);
  }

  /**
   * Проверить код и подтвердить ресурс, зарегистрированный в AuthDTOCollection
   */
  public function confirmVerification(Request $request)
  {
    $entityName = $request->entity;
    $dto = $this->getAuthDTO($entityName);
    $purpose = "prp_verify_$entityName";
    $code = $request->code;

    $user = auth()->user();
    throw_if(
      !Confirmation::validateCode($purpose, $user->id, $code),
      new UnauthorizedHttpException('', __('validation.incorrectLink'))
    );

    $user = User::getBy($entityName, $user->$entityName);
    $user->update([
      $dto->verifiedColumName => Carbon::now()
    ]);
    Confirmation::deleteForPurpose($user, $purpose);

    return response([
      'ok' => true,
      'message' => __('general.verificationEntityVerified', ['entity' => $entityName])
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
