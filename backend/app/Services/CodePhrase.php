<?php

namespace App\Services;

use \Carbon\Carbon;
use Hash;

class CodePhrase
{
  protected static $chars = '-#$!%^&*;.qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';

  protected static $numericChars = '0123456789';

  public static function getRandomLength(int $min = 5, int $max = 10)
  {
    return random_int($min, $max);
  }

  public static function generatePhrase($length = null)
  {
    $charsSplit = mb_str_split(self::$chars);
    $count = count($charsSplit);
    if (!$length)
      $length = self::getRandomLength();

    $phrase = '';
    for ($i = 0; $i < $length; $i++) {
      $j = random_int(0, $count - 1);
      $char = $charsSplit[$j];
      $phrase .= $char;
    }

    return $phrase;
  }

  public static function generateNumeric($length = null)
  {
    $charsSplit = mb_str_split(self::$numericChars);
    $charsCount = count($charsSplit);

    if (!$length)
      $length = self::getRandomLength();

    $phrase = '';
    for ($i = 0; $i < $length; $i++) {
      $char = $charsSplit[random_int(0, $charsCount - 1)];
      $phrase .= $char;
    }
    
    return $phrase;
  }
}
