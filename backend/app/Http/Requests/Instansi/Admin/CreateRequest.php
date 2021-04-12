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
            'nama'        => ['required', 'string', 'max:50'],
            'email'       => ['required', 'email'],
            'kelamin'     => ['nullable', 'string', 'in:L,P'],
            'tmp_lahir'   => ['nullable', 'string'],
            'tgl_lahir'   => ['nullable', 'date_format:Y-m-d'],
            'nip'         => ['nullable', 'numeric'],
            'no_telpon'   => ['nullable', 'numeric'],
            'no_hp'       => ['nullable', 'numeric'],
            'golongan'    => ['nullable', 'string', 'max:100'],
            'k_group'     => ['required', 'exists:m_group,k_group'],
            'instansi_id' => ['nullable', 'sometimes', 'exists:instansi,instansi_id'],
        ];
    }
}
