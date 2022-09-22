<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class CpfOrCnpj implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if ( !in_array(strlen($value), [11, 14]) ) {
            $fail('The :attribute must be 11 if it is a CPF or 14 digits if CNPJ.');
        }
    }
}
