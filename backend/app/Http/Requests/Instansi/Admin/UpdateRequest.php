<?php

namespace App\Http\Requests\Instansi\Admin;

use App\Models\PaudAdmin;
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
        /** @var PaudAdmin $paudAdmin */
        $paudAdmin = $this->route('paudAdmin');

        return [
            'nama'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', "unique:akun,email,{$paudAdmin->akun_id},akun_id"],
            'k_group'    => ['required', 'integer', 'exists:m_group,k_group'],
            'kelamin'    => ['nullable', 'string', 'in:L,P'],
            'tmp_lahir'  => ['nullable', 'string', 'max:50'],
            'tgl_lahir'  => ['nullable', 'date_format:Y-m-d'],
            'nip'        => ['nullable', 'string', 'max:20'],
            'no_telpon'  => ['nullable', 'numeric'],
            'no_hp'      => ['nullable', 'numeric'],
            'k_golongan' => ['nullable', 'integer', 'exists:m_golongan,k_golongan'],
        ];
    }
}
