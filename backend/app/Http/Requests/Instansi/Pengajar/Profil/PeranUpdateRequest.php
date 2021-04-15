<?php

namespace App\Http\Requests\Instansi\Pengajar\Profil;

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
class PeranUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_pembimbing' => ['required', 'in:0,1'],
        ];
    }
}
