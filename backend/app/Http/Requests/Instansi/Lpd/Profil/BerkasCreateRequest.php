<?php

namespace App\Http\Requests\Instansi\Lpd\Profil;

use App\Exceptions\FlowException;
use App\Models\MBerkasLpdPaud;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BerkasCreateRequest
 *
 * @property-read $k_berkas_lpd_paud
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
        $mBerkas = MBerkasLpdPaud::find($this->k_berkas_lpd_paud);
        if (!$mBerkas) {
            throw new FlowException("Jenis Berkas tidak valid");
        }

        $this->berkasAtributes = [
            'file' => $mBerkas->keterangan
        ];

        return [
            'k_berkas_lpd_paud' => ['required', 'integer'],
            'file'              => array_merge(['required', 'file'], explode('|', $mBerkas->validasi)),
        ];
    }
}
