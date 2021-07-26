<?php

namespace App\Http\Requests\Instansi\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PengajarTambahanRequest
 * @package App\Http\Requests\Instansi\Admin
 *
 * @property-read int $k_unsur_pengajar_paud
 */
class PengajarTambahanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'k_unsur_pengajar_paud' => ['required', 'exists:m_unsur_pengajar_paud,k_unsur_pengajar_paud'],
        ];
    }
}
