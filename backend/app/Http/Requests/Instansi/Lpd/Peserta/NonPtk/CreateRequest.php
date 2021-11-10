<?php

namespace App\Http\Requests\Instansi\Lpd\Peserta\NonPtk;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * Class BerkasRequest
 *
 * @property-read string $nama
 * @property-read string $nik
 * @property-read string $tmp_lahir
 * @property-read Carbon $tgl_lahir
 * @property-read string $kelamin
 * @property-read string $email
 * @property-read string $no_hp
 * @property-read string $alamat
 * @property-read int $k_propinsi
 * @property-read int $k_kota
 * @property-read string $unit_kerja
 * @property-read int $k_diklat_paud
 * @property-read int $k_jenjang_diklat_paud
 * @property-read UploadedFile $file_ijazah
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
            'nama'                  => ['required', 'string', 'max:100'],
            'nik'                   => ['required', 'string', 'max:16'],
            'tmp_lahir'             => ['nullable', 'string', 'max:50'],
            'tgl_lahir'             => ['nullable', 'date_format:Y-m-d'],
            'kelamin'               => ['nullable', 'string', 'in:L,P'],
            'email'                 => ['required', 'email'],
            'no_hp'                 => ['nullable', 'numeric'],
            'alamat'                => ['required', 'string', 'max:255'],
            'k_propinsi'            => ['required', 'integer', 'exists:m_propinsi,k_propinsi'],
            'k_kota'                => ['required', 'integer', 'exists:m_kota,k_kota'],
            'unit_kerja'            => ['nullable', 'string', 'max:255'],
            'k_diklat_paud'         => ['required', 'integer', 'exists:m_diklat_paud,k_diklat_paud'],
            'k_jenjang_diklat_paud' => ['required', 'integer', 'exists:m_jenjang_diklat_paud,k_jenjang_diklat_paud'],
            'file_sertifikat'       => ['required', 'file', 'mimes:jpg,jpeg,png,gif', 'between:20,1536'],
            'file_ktp'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'between:20,1536'],
        ];
    }
}
