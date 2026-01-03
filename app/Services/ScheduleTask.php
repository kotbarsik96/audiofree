<?php

namespace App\Services;

use App\Enums\Order\OrderStatusEnum;
use App\Models\Order\Order;
use App\Models\SupportChat\SupportChatWritingStatus;
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

  public static function setOrderStatus()
  {
    Order::where(
      'created_at',
      '<=',
      Carbon::now()->subHours(1)->toDateTimeString()
    )
      ->where('order_status', OrderStatusEnum::PREPARING->value)
      ->update(['order_status' => OrderStatusEnum::DELIVERING]);

    Order::where(
      'created_at',
      '<=',
      Carbon::now()->subHours(2)->toDateTimeString()
    )
      ->where('order_status', OrderStatusEnum::DELIVERING->value)
      ->update(['order_status' => OrderStatusEnum::AWAITING_PICKUP]);

    Order::where(
      'created_at',
      '<=',
      Carbon::now()->subHours(3)->toDateTimeString()
    )
      ->where('order_status', OrderStatusEnum::AWAITING_PICKUP->value)
      ->update(['order_status' => OrderStatusEnum::PICKED_UP]);
  }

  public static function clearExpiredSupportChatWritingStatuses()
  {
    SupportChatWritingStatus::where('started_writing_at', '<=', Carbon::now()->addSeconds(8))
      ->get()
      ->each(function (SupportChatWritingStatus $status) {
        // each() нужен для того, чтобы стриггерить SupportChatWriteStatusEvent
        $status->update([
          'started_writing_at' => null
        ]);
      });
  }
}
