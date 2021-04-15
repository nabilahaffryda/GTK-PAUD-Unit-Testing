<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Class FotoRequest
 * @package App\Http\Requests
 *
 * @property-read string $data
 * @property-read string $ext
 */
class FotoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'foto' => ['nullable', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'foto' => 'Data Foto'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $data = $validator->getData();
            if (!isset($data['foto']) || !$data['foto']) {
                return;
            }

            if (preg_match('/data:image\/(gif|jpeg|png);base64,(.*)/i', $data['foto'], $matches)) {
                $ext = $matches[1];
                $raw = base64_decode($matches[2], true);

                if (!$raw || !$ext) {
                    $validator->errors()->add('foto', 'Berkas Foto tidak valid');
                } else {
                    $this->request->set('data', $raw);
                    $this->request->set('ext', $ext);
                }
            } else {
                $validator->errors()->add('foto', 'Format unggah tidak valid');
            }
        });
    }
}
