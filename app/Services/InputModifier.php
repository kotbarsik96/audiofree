<?php

namespace App\Services;
use ElForastero\Transliterate\Facade as Transliterate;
use Illuminate\Http\Request;

class InputModifier
{
  public static function stringToNumber(string|null $value)
  {
    return $value ? (int) preg_replace('/[^\.0-9]/', '', $value) : null;
  }

  public static function getSlugFromRequest(Request $request, string $fallbackInputKey = 'name')
  {
    $str = $request->slug ? $request->input('slug') : $request->input($fallbackInputKey);
    $explode = explode('-', $str);
    return Transliterate::slugify(
      implode(' ', $explode)
    );
  }
}
