<?php

namespace App\Http\Requests\Instansi\Pengajar\Profil;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 *
 * @property-read $is_pembimbing
 *
 * @package App\Http\Requests\Instansi\PaudInstansi
 */
class PeranUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_pembimbing' => ['required', 'in:0,1'],
        ];
    }
}
