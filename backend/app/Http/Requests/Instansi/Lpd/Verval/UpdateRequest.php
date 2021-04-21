<?php

namespace App\Http\Requests\Instansi\Lpd\Verval;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @property-read $k_verval_paud
 * @package App\Http\Requests\Instansi\Lpd\Verval
 */
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
            'k_verval_paud' => ['required', 'integer', 'exists:m_verval_paud,k_verval_paud'],
            'alasan'        => ['string', 'max:32000'],
        ];
    }
}
