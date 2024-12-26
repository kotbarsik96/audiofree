<?php

namespace App\Validations;

use Illuminate\Validation\Rules\Password;

class AuthValidation
{
  public static function password()
  {
    return [
      'confirmed',
      Password::min(6)->mixedCase()->numbers()
    ];
  }

  public static function passwordNullable()
  {
    return array_merge(
      self::password(),
      ['nullable']
    );
  }

  public static function email()
  {
    return ['email:rfc,dns', 'unique:users', 'max:255'];
  }

  public static function emailRequiredWithout()
  {
    return array_merge(
      self::email(),
      ['required_without:telegram']
    );
  }

  public static function telegram()
  {
    return ['regex:/@[a-zA-Z0-9_]/', 'unique:users'];
  }

  public static function telegramRequiredWithout()
  {
    array_merge(
      self::telegram(),
      ['required_without:email']
    );
  }

  public static function name()
  {
    return 'required|min:2|max:255';
  }

  public static function userField()
  {
    return 'min:0';
  }

  public static function phoneNumber()
  {
    return ['regex:/^\+7 \(\d\d\d\) \d\d\d \d\d \d\d$/'];
  }

  public static function messages()
  {
    return [
      'password.required' => __('validation.password.required'),
      'password.min' => __('validation.password.min'),
      'password.mixed' => __('validation.password.mixed'),
      'password.numbers' => __('validation.password.numbers'),
      'email.required' => __('validation.email.required'),
      'email.email' => __('validation.email.email'),
      'email.unique' => __('validation.email.unique'),
      'name.required' => __('validation.username.required'),
      'name.min' => __('validation.name.min'),
      'phone_number' => __('validation.phone_number.regex')
    ];
  }
}
