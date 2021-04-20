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
            'angkatan' => ['required', 'integer'],
        ];
    }
}
