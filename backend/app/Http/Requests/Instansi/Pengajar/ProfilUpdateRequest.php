<?php

namespace App\Http\Requests\Instansi\Pengajar;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 *
 * @property-read $nama
 * @property-read $nik
 * @property-read $nip
 * @property-read $tmp_lahir
 * @property-read $tgl_lahir
 * @property-read $kelamin
 * @property-read $lulusan
 * @property-read $prodi
 * @property-read $k_kualifikasi
 * @property-read $k_propinsi
 * @property-read $k_kota
 * @property-read $kodepos
 * @property-read $alamat
 * @property-read $k_pcp_paud
 * @property-read $no_hp
 * @property-read $instansi_nama
 * @property-read $instansi_jabatan
 * @property-read $instansi_alamat
 * @property-read $instansi_k_propinsi
 * @property-read $instansi_k_kota
 * @property-read $instansi_kodepos
 * @property-read $pengalaman
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
            'nama'                => ['required', 'string', 'max:100'],
            'nik'                 => ['required', 'digits:16'],
            'nip'                 => ['required', 'digits_between:10,30'],
            'tmp_lahir'           => ['required', 'string', 'max:50'],
            'tgl_lahir'           => ['required', 'date_format:Y-m-d'],
            'kelamin'             => ['required', 'in:L,P'],
            'lulusan'             => ['required', 'string', 'max:100'],
            'prodi'               => ['required', 'string', 'max:50'],
            'k_kualifikasi'       => ['required', 'integer', 'exists:m_kualifikasi,k_kualifikasi', 'in:9,10,11'],
            'k_propinsi'          => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'              => ['required', 'integer', 'exists:m_kota,k_kota'],
            'kodepos'             => ['nullable', 'digits_between:5,6'],
            'alamat'              => ['required', 'string', 'max:255'],
            'k_pcp_paud'          => ['required', 'integer', 'exists:m_pcp_paud,k_pcp_paud'],
            'pcp_paud_lain'       => ['nullable', 'string', 'max:100'],
            'no_hp'               => ['required', 'digits_between:5,20'],
            'instansi_nama'       => ['required', 'string', 'max:100'],
            'instansi_jabatan'    => ['required', 'string', 'max:100'],
            'instansi_alamat'     => ['required', 'string', 'max:100'],
            'instansi_k_propinsi' => ['required', 'string', 'exists:m_propinsi,k_propinsi'],
            'instansi_k_kota'     => ['required', 'string', 'exists:m_kota,k_kota'],
            'instansi_kodepos'    => ['required', 'string', 'digits_between:5,6'],
            'pengalaman'          => ['required', 'array', 'min:1'],
            'pengalaman.*.nama'   => ['required', 'string', 'max:100'],
            'pengalaman.*.tahun'  => ['required', 'string', 'max:50'],
        ];
    }
}
