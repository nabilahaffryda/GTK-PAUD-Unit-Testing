<?php

namespace App\Http\Requests\Instansi\Lpd\Kelas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property-read UploadedFile $file
 */
class UploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => ['required', 'file', 'max:10240'],
        ];
    }
}
