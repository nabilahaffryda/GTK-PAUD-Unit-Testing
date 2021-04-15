<?php

namespace App\Http\Requests\Instansi\Pengajar;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 *
 * @property-read $nama
 * @property-read $email
 * @property-read $alamat
 * @property-read $k_propinsi
 * @property-read $k_kota
 * @property-read $kodepos
 * @property-read $nama_penanggung_jawab
 * @property-read $telp_penanggung_jawab
 *
 * @package App\Http\Requests\Instansi\PaudInstansi
 */
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
            'nama'           => ['required', 'string', 'max:100'],
            'nik'            => ['required', 'digits:16'],
            'tmp_lahir'      => ['required', 'string', 'max:50'],
            'tgl_lahir'      => ['required', 'date_format:Y-m-d'],
            'alamat'         => ['required', 'string', 'max:255'],
            'k_propinsi'     => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'         => ['required', 'integer', 'exists:m_kota,k_kota'],
            'kodepos'        => ['nullable', 'digits_between:5,6'],
            'no_hp'          => ['required', 'digits_between:5,20'],
            'k_kualifikasi'  => ['required', 'integer', 'exists:m_kualifikasi,k_kualifikasi'],
            'instansi_lulus' => ['required', 'string', 'max:100'],
            'pengalaman'     => ['required', 'in:1,2,3,4,5,6'],
            'is_pcp'         => ['required', 'in:0,1'],
        ];
    }
}
