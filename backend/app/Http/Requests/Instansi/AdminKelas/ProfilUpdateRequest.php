<?php

namespace App\Http\Requests\Instansi\AdminKelas;

use Illuminate\Foundation\Http\FormRequest;

class ProfilUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama'       => ['required', 'string', 'max:100'],
            'nik'        => ['required', 'digits:16'],
            'tmp_lahir'  => ['required', 'string', 'max:50'],
            'tgl_lahir'  => ['required', 'date_format:Y-m-d'],
            'kelamin'    => ['required', 'in:L,P'],
            'k_propinsi' => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'     => ['required', 'integer', 'exists:m_kota,k_kota'],
            'kodepos'    => ['nullable', 'digits_between:5,6'],
            'alamat'     => ['required', 'string', 'max:255'],
            'no_hp'      => ['required', 'digits_between:5,20'],
        ];
    }
}
