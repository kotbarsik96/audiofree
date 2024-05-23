<?php

namespace App\Models;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilterableModel extends Model
{
  use HasFactory;

  public function scopeFilter(Builder $builder, QueryFilter $request)
  {
    return $request->apply($builder);
  }
}
