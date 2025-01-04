<?php

namespace App\DTO\Auth;

class AuthDTO
{
  public function __construct(
    public string $columnName, // название столбца в базе данных users
    public string $loginAble, // LoginMailable, LoginTelegramable...
    public string|false $verifiedColumName, // email_verified_at, ... false - если нельзя подтвердить
    public string|false $verifyAble, // VerifyEmailMailable, ... false - если нельзя подтвердить
  ) {
  }
}