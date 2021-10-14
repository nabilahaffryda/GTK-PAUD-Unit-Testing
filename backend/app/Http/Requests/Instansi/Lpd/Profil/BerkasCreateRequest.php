<?php

namespace App\Http\Requests\Instansi\Lpd\Profil;

use App\Exceptions\FlowException;
use App\Models\MBerkasLpdPaud;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BerkasCreateRequest
 *
 * @property-read $k_berkas
 * @property-read $file
 * @package App\Http\Requests\Instansi\Lpd\Profil
 */
class BerkasCreateRequest extends FormRequest
{
    protected $berkasAtributes = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mBerkas = MBerkasLpdPaud::find($this->k_berkas);
        if (!$mBerkas) {
            throw new FlowException("Jenis Berkas tidak valid");
        }

        $this->berkasAtributes = [
            'file' => $mBerkas->keterangan,
        ];

        return [
            'k_berkas' => ['required', 'integer'],
            'file'     => array_merge(['required', 'file'], explode('|', $mBerkas->validasi)),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.mimes' => 'Jenis file yang diunggah harus bertipe :values.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'file' => 'file unggahan',
        ];
    }
}
