<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users');
      $table->json('orderer_data')
        ->comment('Данные о заказчике: имя, средство связи');
      $table->string('delivery_place')
        ->comment('Место выдачи (DeliveryPlaceEnum)');
      $table->string('delivery_address');
      $table->string('order_status')
        ->comment('Статус заказа (OrderStatusEnum)');
      $table->string('desired_payment_type')
        ->comment('Желаемый тип оплаты (PaymentTypeEnum)');
      $table->boolean('is_paid')
        ->comment('Оплачен ли заказ');
      $table->string('image')
        ->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
