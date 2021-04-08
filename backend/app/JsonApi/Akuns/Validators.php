<?php

namespace App\JsonApi\Akuns;

use App\Models\Akun;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'nip',
        'nama',
        'passwd',
        'tmp_lahir',
        'tgl_lahir',
        'no_telpon',
        'no_hp',
        'email',
        'kelamin',
        'foto',
        'k_golongan',
        'moodle_id',
        'paspor_id',
        'token',
        'jabatan',
        'instansi_asal',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_alamat',
        'instansi_kodepos',
        'alamat',
        'k_propinsi',
        'k_kota',
        'kodepos',
        'nik',
        'npwp',
        'rekening_nama',
        'rekening_bank',
        'rekening_cabang',
        'rekening_nomor',
        'k_jabatan_guru',
        'k_jabatan_dosen_ppg',
        'gelar_depan',
        'gelar_belakang',
        'is_aktif',
        'k_status_email',
        'admin_id',
    ];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'instansi_kota',
        'instansi_propinsi',
        'kota',
        'm_propinsi',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'akun_id',
        'nip',
        'nama',
        'passwd',
        'tmp_lahir',
        'tgl_lahir',
        'no_telpon',
        'no_hp',
        'email',
        'kelamin',
        'foto',
        'k_golongan',
        'moodle_id',
        'paspor_id',
        'token',
        'jabatan',
        'instansi_asal',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_alamat',
        'instansi_kodepos',
        'alamat',
        'k_propinsi',
        'k_kota',
        'kodepos',
        'nik',
        'npwp',
        'rekening_nama',
        'rekening_bank',
        'rekening_cabang',
        'rekening_nomor',
        'k_jabatan_guru',
        'k_jabatan_dosen_ppg',
        'gelar_depan',
        'gelar_belakang',
        'is_aktif',
        'k_status_email',
        'admin_id',
    ];

    /**
     * The allowed filtering parameters.
     *
     * By default we set this to `null` to allow any filtering parameters, as we expect
     * the filtering parameters to be validated using the query parameter validator.
     *
     * Empty array = clients are not allowed to request filtering.
     * Null = clients can specify any filtering fields they want.
     *
     * @var string[]|null
     * @todo 3.0.0 make this `[]` by default, as we now loop through filter parameters.
     */
    protected $allowedFilteringParameters = [
        'akun_id',
        'nip',
        'nama',
        'passwd',
        'tmp_lahir',
        'tgl_lahir',
        'no_telpon',
        'no_hp',
        'email',
        'kelamin',
        'foto',
        'k_golongan',
        'moodle_id',
        'paspor_id',
        'token',
        'jabatan',
        'instansi_asal',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_alamat',
        'instansi_kodepos',
        'alamat',
        'k_propinsi',
        'k_kota',
        'kodepos',
        'nik',
        'npwp',
        'rekening_nama',
        'rekening_bank',
        'rekening_cabang',
        'rekening_nomor',
        'k_jabatan_guru',
        'k_jabatan_dosen_ppg',
        'gelar_depan',
        'gelar_belakang',
        'is_aktif',
        'k_status_email',
        'admin_id',
    ];

    /**
     * Get resource validation rules.
     *
     * @param Akun|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'nip'                 => ['sometimes', 'nullable', 'string'],
            'nama'                => ['required', 'string'],
            'passwd'              => ['sometimes', 'nullable', 'string'],
            'tmp_lahir'           => ['sometimes', 'nullable', 'string'],
            'tgl_lahir'           => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'no_telpon'           => ['sometimes', 'nullable', 'string'],
            'no_hp'               => ['sometimes', 'nullable', 'string'],
            'email'               => ['required', 'string'],
            'kelamin'             => ['sometimes', 'nullable', 'string'],
            'foto'                => ['sometimes', 'nullable', 'string'],
            'k_golongan'          => ['sometimes', 'nullable', 'integer'],
            'moodle_id'           => ['sometimes', 'nullable', 'integer'],
            'paspor_id'           => ['sometimes', 'nullable', 'integer'],
            'token'               => ['sometimes', 'nullable', 'string'],
            'jabatan'             => ['sometimes', 'nullable', 'string'],
            'instansi_asal'       => ['sometimes', 'nullable', 'string'],
            'instansi_k_propinsi' => ['sometimes', 'nullable', 'integer'],
            'instansi_k_kota'     => ['sometimes', 'nullable', 'integer'],
            'instansi_alamat'     => ['sometimes', 'nullable', 'string'],
            'instansi_kodepos'    => ['sometimes', 'nullable', 'string'],
            'alamat'              => ['sometimes', 'nullable', 'string'],
            'k_propinsi'          => ['sometimes', 'nullable', 'integer'],
            'k_kota'              => ['sometimes', 'nullable', 'integer'],
            'kodepos'             => ['sometimes', 'nullable', 'string'],
            'nik'                 => ['sometimes', 'nullable', 'string'],
            'npwp'                => ['sometimes', 'nullable', 'string'],
            'rekening_nama'       => ['sometimes', 'nullable', 'string'],
            'rekening_bank'       => ['sometimes', 'nullable', 'string'],
            'rekening_cabang'     => ['sometimes', 'nullable', 'string'],
            'rekening_nomor'      => ['sometimes', 'nullable', 'string'],
            'k_jabatan_guru'      => ['sometimes', 'nullable', 'integer'],
            'k_jabatan_dosen_ppg' => ['sometimes', 'nullable', 'integer'],
            'gelar_depan'         => ['sometimes', 'nullable', 'string'],
            'gelar_belakang'      => ['sometimes', 'nullable', 'string'],
            'is_aktif'            => ['required', 'string'],
            'k_status_email'      => ['required', 'integer'],
            'admin_id'            => ['sometimes', 'nullable', 'string'],
        ];
    }

    /**
     * Get query parameter validation rules.
     *
     * @return array
     */
    protected function queryRules(): array
    {
        return [
            'filter.nip'                 => ['sometimes', 'string'],
            'filter.nama'                => ['sometimes', 'string'],
            'filter.passwd'              => ['sometimes', 'string'],
            'filter.tmp_lahir'           => ['sometimes', 'string'],
            'filter.tgl_lahir'           => ['sometimes', 'date_format:Y-m-d'],
            'filter.no_telpon'           => ['sometimes', 'string'],
            'filter.no_hp'               => ['sometimes', 'string'],
            'filter.email'               => ['sometimes', 'string'],
            'filter.kelamin'             => ['sometimes', 'string'],
            'filter.foto'                => ['sometimes', 'string'],
            'filter.k_golongan'          => ['sometimes', 'integer'],
            'filter.moodle_id'           => ['sometimes', 'integer'],
            'filter.paspor_id'           => ['sometimes', 'integer'],
            'filter.token'               => ['sometimes', 'string'],
            'filter.jabatan'             => ['sometimes', 'string'],
            'filter.instansi_asal'       => ['sometimes', 'string'],
            'filter.instansi_k_propinsi' => ['sometimes', 'integer'],
            'filter.instansi_k_kota'     => ['sometimes', 'integer'],
            'filter.instansi_alamat'     => ['sometimes', 'string'],
            'filter.instansi_kodepos'    => ['sometimes', 'string'],
            'filter.alamat'              => ['sometimes', 'string'],
            'filter.k_propinsi'          => ['sometimes', 'integer'],
            'filter.k_kota'              => ['sometimes', 'integer'],
            'filter.kodepos'             => ['sometimes', 'string'],
            'filter.nik'                 => ['sometimes', 'string'],
            'filter.npwp'                => ['sometimes', 'string'],
            'filter.rekening_nama'       => ['sometimes', 'string'],
            'filter.rekening_bank'       => ['sometimes', 'string'],
            'filter.rekening_cabang'     => ['sometimes', 'string'],
            'filter.rekening_nomor'      => ['sometimes', 'string'],
            'filter.k_jabatan_guru'      => ['sometimes', 'integer'],
            'filter.k_jabatan_dosen_ppg' => ['sometimes', 'integer'],
            'filter.gelar_depan'         => ['sometimes', 'string'],
            'filter.gelar_belakang'      => ['sometimes', 'string'],
            'filter.is_aktif'            => ['sometimes', 'string'],
            'filter.k_status_email'      => ['sometimes', 'integer'],
            'filter.admin_id'            => ['sometimes', 'string'],
            'page.number'                => ['integer', 'min:1'],
            'page.size'                  => ['integer', 'between:1,50'],
        ];
    }
}
