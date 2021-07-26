<?php

namespace App\Http\Requests\Instansi\Diklat\Periode;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Instansi\Diklat\Periode
 *
 * @property-read string $nama
 * @property-read Carbon $tgl_mulai
 * @property-read Carbon $tgl_selesai
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
            'nama'        => ['required', 'string'],
            'tgl_mulai'   => ['required', 'date_format:Y-m-d'],
            'tgl_selesai' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
