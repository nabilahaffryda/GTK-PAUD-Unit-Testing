<?php

namespace App\Http\Requests\Instansi\Lpd\Verval;

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
            'filter'                 => ['nullable', 'array'],
            'filter.k_verval_paud'   => ['nullable', 'array', 'min:1'],
            'filter.k_verval_paud.*' => ['nullable', 'int', 'exists:m_verval_paud,k_verval_paud'],
            'keyword'                => ['nullable', 'string', 'min:3', 'max:100'],
            'count'                  => ['integer', 'max:50'],
            'page'                   => ['integer', 'min:1'],
        ];
    }
}
