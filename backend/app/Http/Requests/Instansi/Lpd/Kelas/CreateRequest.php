<?php

namespace App\Http\Requests\Instansi\Lpd\Kelas;

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
            'nama'                => ['required', 'string'],
            'deskripsi'           => ['nullable', 'string'],
            'paud_mapel_kelas_id' => ['required', 'integer', 'exists:paud_mapel_kelas,paud_mapel_kelas_id'],
            'k_propinsi'          => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'              => ['required', 'integer', 'exists:m_kota,k_kota'],
            'k_kecamatan'         => ['required', 'integer', 'exists:m_kecamatan,k_kecamatan'],
            'k_kelurahan'         => ['required', 'integer', 'exists:m_kelurahan,k_kelurahan'],
        ];
    }
}