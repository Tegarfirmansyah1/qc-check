<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class SumOfResultsEqualsFail implements ValidationRule
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Jalankan aturan validasi.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $totalFailOnHeader = (int) $this->request->input('quantity_fail');

        // Jika tidak ada item yang gagal di header, tidak perlu validasi lebih lanjut.
        if ($totalFailOnHeader === 0) {
            return;
        }

        $sumOfFailResults = 0;
        if (is_array($value)) {
            foreach ($value as $result) {
                // Tambahkan nilai fail_count jika ada dan merupakan angka
                if (isset($result['fail_count']) && is_numeric($result['fail_count'])) {
                    $sumOfFailResults += (int) $result['fail_count'];
                }
            }
        }

        if ($sumOfFailResults !== $totalFailOnHeader) {
            $fail('Total dari rincian "Jumlah Gagal Karena Ini" ('.$sumOfFailResults.') harus sama dengan "Jumlah Gagal" di rekapitulasi ('.$totalFailOnHeader.').');
        }
    }
}
