<?php

namespace App\Http\Requests\Instansi\Petugas;

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
            'nama'                => ['required', 'string', 'max:100'],
            'nik'                 => ['required', 'digits:16'],
            'nip'                 => ['nullable', 'digits_between:10,30'],
            'tmp_lahir'           => ['required', 'string', 'max:50'],
            'tgl_lahir'           => ['required', 'date_format:Y-m-d'],
            'kelamin'             => ['required', 'in:L,P'],
            'lulusan'             => ['required', 'string', 'max:100'],
            'prodi'               => ['required', 'string', 'max:50'],
            'k_kualifikasi'       => ['required', 'integer', 'exists:m_kualifikasi,k_kualifikasi', 'in:9,10,11'],
            'k_propinsi'          => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'              => ['required', 'integer', 'exists:m_kota,k_kota'],
            'k_kecamatan'         => ['nullable', 'integer', 'exists:m_kecamatan,k_kecamatan'],
            'k_kelurahan'         => ['nullable', 'integer', 'exists:m_kelurahan,k_kelurahan'],
            'kodepos'             => ['nullable', 'digits_between:5,6'],
            'alamat'              => ['nullable', 'string', 'max:255'],
            'no_hp'               => ['required', 'digits_between:5,20'],
            'instansi_nama'       => ['required', 'string', 'max:100'],
            'instansi_jabatan'    => ['required', 'string', 'max:100'],
            'instansi_alamat'     => ['required', 'string', 'max:100'],
            'instansi_k_propinsi' => ['required', 'string', 'exists:m_propinsi,k_propinsi'],
            'instansi_k_kota'     => ['required', 'string', 'exists:m_kota,k_kota'],
            'instansi_kodepos'    => ['nullable', 'string', 'digits_between:5,6'],
        ];
    }
}
