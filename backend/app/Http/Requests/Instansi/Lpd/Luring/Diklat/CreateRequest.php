<?php

namespace App\Http\Requests\Instansi\Lpd\Luring\Diklat;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'k_diklat_paud'         => ['required', 'integer', 'exists:m_diklat_paud,k_diklat_paud'],
            'k_jenjang_diklat_paud' => ['required', 'integer', 'exists:m_jenjang_diklat_paud,k_jenjang_diklat_paud'],
            'nama'                  => ['required', 'string', 'max:100'],
            'singkatan'             => ['nullable', 'string', 'max:50'],
            'deskripsi'             => ['nullable', 'string'],
            'k_propinsi'            => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'                => ['required', 'integer', 'exists:m_kota,k_kota'],
            'tgl_mulai'             => ['required', 'date'],
            'tgl_selesai'           => ['required', 'date'],
        ];
    }
}
