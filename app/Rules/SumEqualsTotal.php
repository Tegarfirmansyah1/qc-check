<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class SumEqualsTotal implements ValidationRule
{
    protected $request;

    /**
     * Create a new rule instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $total = (int) $this->request->input('quantity_total');
        $pass = (int) $this->request->input('quantity_pass');
        $failQty = (int) $this->request->input('quantity_fail');

        if (($pass + $failQty) !== $total) {
            $fail('Total dari "Jumlah Lulus" dan "Jumlah Gagal" harus sama dengan "Jumlah Total Diperiksa".');
        }
    }
}
