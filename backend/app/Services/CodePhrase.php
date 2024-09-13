<?php

namespace App\Services;

use \Carbon\Carbon;

class CodePhrase
{
  protected static $chars = '-#$!%^&*;.qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';

  public static function generate($length = null, $mustBeHashed = true)
  {
    $charsSplit = mb_str_split(self::$chars);
    $count = count($charsSplit);
    if (!$length) $length = random_int(15, 20);

    $phrase = '';
    for ($i = 0; $i < $length; $i++) {
      $j = random_int(0, $count - 1);
      $char = $charsSplit[$j];
      $phrase .= $char;
    }

    if($mustBeHashed) return md5(Carbon::now()->timestamp . rand() . $phrase);
    return $phrase;
  }
}
