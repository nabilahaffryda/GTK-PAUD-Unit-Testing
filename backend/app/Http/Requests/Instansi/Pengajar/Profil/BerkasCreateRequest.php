<?php

namespace App\Http\Requests\Instansi\Pengajar\Profil;

use App\Exceptions\FlowException;
use App\Models\MBerkasPengajarPaud;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Class BerkasRequest
 *
 * @property-read int $k_berkas
 * @property-read UploadedFile $file
 *
 * @package Modules\Psp\Http\Requests\Gtk\Profil\Profil
 */
class BerkasCreateRequest extends FormRequest
{
    protected $berkasAtributes = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws FlowException
     */
    public function rules()
    {
        $mBerkas = MBerkasPengajarPaud::whereKBerkasPengajarPaud($this->k_berkas)->first();
        if (!$mBerkas) {
            throw new FlowException("Jenis Berkas tidak valid");
        }

        $this->berkasAtributes = [
            'file' => $mBerkas->keterangan
        ];

        return [
            'k_berkas' => ['required', 'int'],
            'file'     => array_merge(['required', 'file'], explode('|', $mBerkas->validation)),
        ];
    }

    public function attributes()
    {
        return $this->berkasAtributes;
    }
}
