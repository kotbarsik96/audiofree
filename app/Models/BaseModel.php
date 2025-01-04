<?php

namespace App\Models;

use App\Traits\CanUseTableNameStatically;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
  use CanUseTableNameStatically;
}
