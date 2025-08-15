<?php

namespace App\Http\Requests\Order;

use App\Enums\Order\PaymentTypeEnum;
use App\Rules\AddressMustExist;
use App\Services\StringsService;
use App\Validations\AuthValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !!auth()->user();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $paymentTypes = StringsService::enumToStringsArray(PaymentTypeEnum::cases());

        return [
            'orderer_name' => 'required|string',
            'email' => 'required_without_all:telegram,phone_number|email|nullable',
            'telegram' => 'required_without_all:email,phone_number|string|nullable',
            'phone_number' => array_merge(
                ['required_without_all:email,telegram', 'nullable'],
                AuthValidation::phoneNumber()
            ),
            'delivery_place' => 'required|string',
            'delivery_address' => ['required', 'string', new AddressMustExist],
            'desired_payment_type' => Rule::in($paymentTypes),
            'is_oneclick' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'orderer_name' => __('validation.order.orderer_name'),
            'email.required_without_all' => __('validation.order.contactMethod'),
            'telegram.required_without_all' => __('validation.order.contactMethod'),
            'phone_number.required_without_all' => __('validation.order.contactMethod'),
            'email.email' => __('validation.email.email'),
            'phone_number.regex' => __('validation.phone_number.regex'),
            'delivery_place' => __('validation.order.delivery_place'),
            'delivery_address.required' => __('validation.order.delivery_address'),
            'desired_payment_type' => __('validation.order.desired_payment_type'),
            'is_oneclick' => __('validation.error')
        ];
    }
}
