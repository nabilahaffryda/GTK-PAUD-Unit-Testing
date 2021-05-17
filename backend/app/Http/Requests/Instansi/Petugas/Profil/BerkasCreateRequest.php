<?php

namespace App\Http\Requests\Instansi\Petugas\Profil;

use App\Exceptions\FlowException;
use App\Models\MBerkasPetugasPaud;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Class BerkasRequest
 *
 * @property-read int $k_berkas
 * @property-read UploadedFile $file
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
        $mBerkas = MBerkasPetugasPaud::whereKBerkasPetugasPaud($this->k_berkas)->first();
        if (!$mBerkas) {
            throw new FlowException("Jenis Berkas tidak valid");
        }

        $this->berkasAtributes = [
            'file' => $mBerkas->keterangan,
        ];

        return [
            'k_berkas'   => ['required', 'int'],
            'keterangan' => ['nullable', 'string'],
            'file'       => array_merge(['required', 'file'], explode('|', $mBerkas->validation)),
        ];
    }

    public function attributes()
    {
        return $this->berkasAtributes;
    }
}
