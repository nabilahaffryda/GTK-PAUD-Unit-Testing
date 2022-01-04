<?php

namespace App\Http\Requests\Instansi\Kelas\Luring\Laporan;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $k_verval_paud
 * @property-read string $alasan
 * @property-read string $no_sertifikat
 * @property-read Carbon $tgl_sertifikat
 */
class VervalRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'k_verval_paud'  => ['required', 'integer', 'in:4,5,6'],
            'alasan'         => ['required_unless:k_verval_paud,6', 'nullable', 'string'],
            'no_sertifikat'  => ['required_if:k_verval_paud,6', 'nullable', 'string', 'max:50'],
            'tgl_sertifikat' => ['required_if:k_verval_paud,6', 'nullable', 'date_format:Y-m-d'],
        ];
    }
}
