<?php

namespace App\Http\Requests\Instansi\Lpd\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class IndexPetugasRequest extends FormRequest
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
            'keyword'        => ['nullable', 'string', 'min:3', 'max:100'],
            'count'          => ['integer', 'max:50'],
            'page'           => ['integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'k_petugas_paud.exists' => 'Tipe Petugas tidak tersedia',
        ];
    }
}
