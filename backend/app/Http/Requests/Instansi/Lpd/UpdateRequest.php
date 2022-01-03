<?php

namespace App\Http\Requests\Instansi\Lpd;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 *
 * @property-read $nama
 * @property-read $email
 * @property-read $ratio_pengajar_tambahan
 *
 * @package App\Http\Requests\Instansi\PaudInstansi
 */
class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama'                    => ['required', 'string', 'max:100'],
            'email'                   => ['required', 'email', 'max:100'],
            'ratio_pengajar_tambahan' => ['required', 'integer', 'min:10', 'max:100'],
            'k_lpd_paud'              => ['required', 'integer', 'exists:m_lpd_paud,k_lpd_paud'],
        ];
    }
}
