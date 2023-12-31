<?php

namespace App\Exceptions;

use Exception;

class RolesExceptions extends Exception
{

    public static function roleNotExists()
    {
        return new self('Такая роль не существует');
    }

    public static function actionNotExists()
    {
        return new self('Такое действие не существует');
    }

    public static function noRights()
    {
        return new self('Нет прав для совершения этого действия');
    }

    public static function noRightsResponse()
    {
        return response(['error' => self::noRights()->getMessage()], 400);
    }
}