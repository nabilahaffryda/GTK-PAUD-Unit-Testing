<?php

namespace App\Http\Requests\Instansi\Lpd\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class CreatePesertaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ptk_id'   => ['required', 'min:1', 'array'],
            'ptk_id.*' => ['required'],
        ];
    }
}
