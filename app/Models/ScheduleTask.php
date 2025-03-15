<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Confirmation;
use Carbon\Carbon;

class ScheduleTask extends Model
{
  use HasFactory;

  public static function clearExpiredConfirmations()
  {
    Confirmation::where('expires', '<=', Carbon::now())
      ->delete();
  }
}
