<?php

namespace App\Http\Requests\Instansi\Petugas\Profil;

use Illuminate\Foundation\Http\FormRequest;

class DiklatCreateRequest extends FormRequest
{
    protected $_attributes = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data'                          => ['required', 'array', 'min:1'],
            'data.*.paud_petugas_diklat_id' => ['nullable', 'int'],
            'data.*.k_diklat_paud'          => ['required_without:data.*.paud_petugas_diklat_id', 'int', 'in:1,2,3,4'],
            'data.*.nama'                   => ['required_if:data.*.k_diklat_paud,4', 'string', 'max:100'],
            'data.*.penyelenggara'          => ['required', 'string', 'max:100'],
            'data.*.k_tingkat_diklat_paud'  => ['required_if:data.*.k_diklat_paud,1,2,3', 'int', 'in:1,2,3'],
            'data.*.tingkatan'              => ['required_if:data.*.k_diklat_paud,4', 'string', 'max:50'],
            'data.*.tahun_diklat'           => ['required', 'int'],
            'data.*.file'                   => ['required_without:data.*.paud_petugas_diklat_id', 'file'],
        ];
    }

    public function attributes()
    {
        return $this->_attributes;
    }
}
