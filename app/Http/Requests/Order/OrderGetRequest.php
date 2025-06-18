<?php

namespace App\Http\Requests\Order;

use App\Models\Order\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderGetRequest extends FormRequest
{
    public ?Order $order;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $this->order = Order::totalCost()->findOrFail($this->route()->parameter('order_id'));
        return auth()->user()?->id ?? 0 === $this->order->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'exists:orders,id'
        ];
    }

    public function messages()
    {
        return [
            'order_id.exists' => __('validation.order.orderNotExists')
        ];
    }
}
