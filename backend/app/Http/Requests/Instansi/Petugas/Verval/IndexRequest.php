<?php

namespace App\Http\Requests\Instansi\Petugas\Verval;

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
            'k_unsur_pengajar_paud' => ['required', 'exists:m_unsur_pengajar_paud,k_unsur_pengajar_paud'],
            'keyword'               => ['string', 'min:3', 'max:100'],
            'count'                 => ['integer', 'max:50'],
            'page'                  => ['integer', 'min:1'],
        ];
    }
}
