<?php

namespace App\Exceptions;

use Exception;

class EmailConfirmationException extends Exception
{
  public function incorrectCode()
  {
    return response([
      'message' => 'Недействительный код',
      400
    ]);
  }
}
