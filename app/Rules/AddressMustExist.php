<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressMustExist implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = trim($value);
        $token = env('DADATA_API_KEY');
        $secret = env('DADATA_SECRET_KEY');
        $dadata = new \Dadata\DadataClient($token, $secret);

        $results = array_map(
            fn($item) => $item['value'],
            $dadata->suggest('address', $value)
        );

        if (!in_array($value, $results)) {
            $fail('validation.address.invalid')->translate();
        }

        $addressClean = $dadata->clean('address', $value);

        if (!$addressClean) {
            $fail('validation.address.addressNotFull')->translate();
        }

        if (!isset($addressClean['house']) || !isset($addressClean['street'])) {
            $fail('validation.address.addressNotFull')->translate();
        }
    }
}
