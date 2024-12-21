<?php

namespace App\Http\Controllers;

use App\Services\InputModifier;
use Illuminate\Http\Request;
use ElForastero\Transliterate\Facade as Transliterate;

class TestController extends Controller
{
  public function test(Request $request)
  {
    // return Transliterate::slugify($request->get('input'));
    return InputModifier::getSlugFromRequest($request);
  }
}
