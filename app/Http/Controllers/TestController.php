<?php

namespace App\Http\Controllers;

use App\DTO\Enums\ConfirmationPurposeEnum;
use App\Filters\QueryFilter;
use App\Models\Cart\Cart;
use App\Models\Confirmation;
use App\Models\Order\Order;
use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class TestController extends Controller
{
  public function test(Request $request)
  {
    
  }
}
