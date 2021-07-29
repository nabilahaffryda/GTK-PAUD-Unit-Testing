<?php

namespace App\Http\Requests\Instansi\Admin\Pengajar;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class SetIntiRequest
 * @package App\Http\Requests\Instansi\Admin\PembimbingPraktik
 *
 * @property-read bool $is_inti
 * @property-read bool $is_bimtek
 */
class ResetPengajarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_inti'    => ['sometimes', 'boolean'],
            'is_bimtek'  => ['sometimes', 'boolean'],
        ];
    }
}
