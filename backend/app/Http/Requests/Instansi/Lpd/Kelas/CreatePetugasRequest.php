<?php

namespace App\Http\Requests\Instansi\Lpd\Kelas;

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
            'k_petugas_paud'  => ['required', 'integer', 'exists:m_petugas_paud,k_petugas_paud'],
            'paud_petugas_id' => ['required', 'integer', 'exists:paud_petugas,paud_petugas_id'],
        ];
    }

    public function messages()
    {
        return [
            'paud_petugas_id.exists' => "Petugas tidak ditemukan"
        ];
    }
}
