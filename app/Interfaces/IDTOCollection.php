<?php

namespace App\Interfaces;

interface IDTOCollection
{
  /** 
   * Возвращает DTO по ключу $enum 
   * 
   * @param $enum - ключ DTO
   * */
  public static function getDTO($enum);
}