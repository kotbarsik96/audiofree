<?php

namespace App\Traits;

trait CanUseTableNameStatically
{
  public static function tableName()
  {
    return with(new static)->getTable();
  }
}
