<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Confirmation;
// use App\Models\Product\ProductVariation;
use Carbon\Carbon;

class ScheduleTask extends Model
{
  use HasFactory;

  public static function clearExpiredConfirmations()
  {
    $now = Carbon::now();
    Confirmation::whereDate('expires', '<=', $now)
      ->whereTime('expires', '<', $now)
      ->delete();
  }
  
  public static function clearInactiveVariations()
  {
    // ProductVariation::where('active', false)
    //   ->notInManyToMany()
    //   ->delete();
  }
}
