<?php

namespace App\Exceptions;

use Exception;

class EmailConfirmationException extends Exception
{
  public function incorrectCode()
  {
    return response([
      'message' => __('validation.incorrectCode'),
      400
    ]);
  }
  public function incorrectLink()
  {
    return response([
      'message' => __('validation.incorrectLink'),
      400
    ]);
  }
}
