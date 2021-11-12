<?php

namespace App\Http\Requests\Instansi\Lpd\Luring\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class CreatePetugasRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'k_petugas_paud' => ['required', 'integer', 'exists:m_petugas_paud,k_petugas_paud'],
            'akun_id'        => ['required', 'min:1', 'array'],
            'akun_id.*'      => ['required', 'int'],
        ];
    }

    public function messages()
    {
        return [
            'akun_id.array' => "Format parameter akun id salah",
        ];
    }
}
