<?php

namespace App\DTO\Auth;

class AuthDTO
{
  public function __construct(
    public string $columnName, // название столбца в базе данных users
    public string $loginAble, // LoginMailable, LoginTelegramable...
    public string $verifiedColumName, // email_verified_at, ...
    public string $verifyAble, // VerifyEmailMailable, ...
  ) {
  }
}