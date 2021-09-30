<?php

namespace App\Http\Requests\Instansi\Lpd\Diklat;

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
            'nama'            => ['required', 'string', 'max:100'],
            'singkatan'       => ['nullable', 'string', 'max:50'],
            'deskripsi'       => ['nullable', 'string'],
            'k_propinsi'      => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'          => ['required', 'integer', 'exists:m_kota,k_kota'],
            'paud_periode_id' => ['required', 'integer', 'exists:paud_periode,paud_periode_id'],
        ];
    }
}
