<?php

namespace App\Http\Requests\Instansi\Lpd;

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
            'nama'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:100'],
            'alamat'                => ['required', 'string', 'max:100'],
            'k_propinsi'            => ['required', 'integer'],
            'k_kota'                => ['required', 'integer'],
            'kodepos'               => ['required', 'string', 'max:6'],
            'nama_penanggung_jawab' => ['required', 'string', 'max:100'],
            'telp_penanggung_jawab' => ['required', 'digits_between:5,20'],
        ];
    }
}
