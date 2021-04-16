<?php

namespace App\Http\Requests\Instansi\Admin;

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
            'nama'        => ['required', 'string', 'max:100'],
            'k_group'     => ['required', 'integer', 'exists:m_group,k_group'],
            'kelamin'     => ['nullable', 'string', 'in:L,P'],
            'tmp_lahir'   => ['nullable', 'string', 'max:50'],
            'tgl_lahir'   => ['nullable', 'date_format:Y-m-d'],
            'nik'         => ['nullable', 'string', 'max:16'],
            'nip'         => ['nullable', 'string', 'max:30'],
            'no_telpon'   => ['nullable', 'numeric'],
            'no_hp'       => ['nullable', 'numeric'],
            'email'       => ['required', 'email'],
            'k_propinsi'  => ['nullable', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'      => ['nullable', 'integer', 'exists:m_kota,k_kota'],
            'k_golongan'  => ['nullable', 'integer', 'exists:m_golongan,k_golongan'],
            'instansi_id' => ['nullable', 'sometimes', 'exists:instansi,instansi_id'],
        ];
    }
}
