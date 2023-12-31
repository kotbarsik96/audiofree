<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\JsonResponse;

class VariationsExceptions extends Exception
{
    public static function storeValidator()
    {
        return [
            'required' => 'Не указано поле ":attribute" для вариации',
            'product_id.exists' => 'Товар с таким id не существует',
            'string' => 'Некорректное значение для поля ":attribute"'
        ];
    }

    public static function noVariation()
    {
        return new self('Такой вариации не существует');
    }

    public static function storeValueValidator()
    {
        return [
            'required' => 'Не указано поле ":attribute"',
            'variation_id.exists' => 'Такой вариации не существует',
            'value.unique' => 'Такое значение вариации уже существует'
        ];
    }

    public static function alreadyExists($variationName)
    {
        return new self('Вариация ' . $variationName . ' уже существует для этого товара');
    }
}