<?php

namespace App\Http\Requests\Instansi\Lpd\Luring\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class CreatePesertaNonPtkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paud_peserta_nonptk_id'   => ['required', 'min:1', 'array'],
            'paud_peserta_nonptk_id.*' => ['required'],
        ];
    }
}
