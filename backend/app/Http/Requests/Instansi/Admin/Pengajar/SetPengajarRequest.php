<?php

namespace App\Http\Requests\Instansi\Admin\Pengajar;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SetIntiRequest
 * @package App\Http\Requests\Instansi\Admin\PembimbingPraktik
 *
 * @property-read array $is_inti
 * @property-read array $is_bimtek
 * @property-read array $akun_ids
 */
class SetPengajarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_inti'    => ['required', 'boolean'],
            'is_bimtek'  => ['required', 'boolean'],
            'akun_ids'   => ['required', 'array', 'min:1'],
            'akun_ids.*' => ['required', 'integer'],
        ];
    }
}
