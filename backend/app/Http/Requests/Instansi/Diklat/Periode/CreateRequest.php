<?php

namespace App\Http\Requests\Instansi\Diklat\Periode;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Instansi\Diklat\Periode
 *
 * @property-read array $data
 */
class CreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data'               => ['required', 'array', 'min:1'],
            'data.*.nama'        => ['required', 'string'],
            'data.*.tgl_mulai'   => ['required', 'date_format:Y-m-d'],
            'data.*.tgl_selesai' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
