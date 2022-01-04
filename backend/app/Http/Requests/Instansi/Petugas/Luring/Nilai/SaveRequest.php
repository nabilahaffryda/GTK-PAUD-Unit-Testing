<?php

namespace App\Http\Requests\Instansi\Petugas\Luring\Nilai;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read array<int, float> $nilai
 */
class SaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nilai'   => ['required', 'array', 'min:1'],
            'nilai.*' => ['required', 'numeric', 'between:0,100'],
        ];
    }
}
