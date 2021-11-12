<?php

namespace App\Http\Requests\Instansi\Lpd\Luring\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class IndexPesertaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'keyword' => ['nullable', 'string', 'min:3', 'max:100'],
            'count'   => ['integer', 'max:50'],
            'page'    => ['integer', 'min:1'],
        ];
    }
}
