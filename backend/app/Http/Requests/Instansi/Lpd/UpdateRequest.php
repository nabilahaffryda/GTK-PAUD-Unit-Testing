<?php

namespace App\Http\Requests\Instansi\Lpd;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama'                  => ['required', 'string', 'max:50'],
            'email'                 => ['required', 'email'],
            'alamat'                => ['required', 'string', 'max:255'],
            'no_telpon'             => ['required', 'string', 'max:255'],
            'k_propinsi'            => ['required', 'integer'],
            'k_kota'                => ['required', 'integer'],
            'kodepos'               => ['required', 'string', 'max:6'],
            'nama_penanggung_jawab' => ['required', 'string', 'max:100'],
            'telp_penanggung_jawab' => ['required', 'digits_between:5,20'],
            'nama_sekretaris'       => ['required', 'string', 'max:100'],
            'telp_sekretaris'       => ['required', 'digits_between:5,20'],
            'nama_bendahara'        => ['required', 'string', 'max:100'],
            'telp_bendahara'        => ['required', 'digits_between:5,20'],
            'diklat'                => ['required', 'array', 'min:1'],
            'diklat.*.nama'         => ['required', 'string', 'max:100'],
            'diklat.*.tahun'        => ['required', 'string', 'max:50'],
        ];
    }
}
