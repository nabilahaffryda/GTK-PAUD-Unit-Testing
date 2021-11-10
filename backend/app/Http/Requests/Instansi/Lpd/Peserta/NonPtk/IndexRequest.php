<?php

namespace App\Http\Requests\Instansi\Lpd\Peserta\NonPtk;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
