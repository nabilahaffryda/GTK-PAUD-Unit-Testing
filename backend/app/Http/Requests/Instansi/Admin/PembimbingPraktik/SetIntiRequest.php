<?php

namespace App\Http\Requests\Instansi\Admin\PembimbingPraktik;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SetIntiRequest
 * @package App\Http\Requests\Instansi\Admin\PembimbingPraktik
 *
 * @property-read array $akun_ids
 */
class SetIntiRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'akun_ids'   => ['required', 'array', 'min:1'],
            'akun_ids.*' => ['required', 'integer'],
        ];
    }
}
