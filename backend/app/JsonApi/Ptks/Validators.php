<?php

namespace App\JsonApi\Ptks;

use App\Models\Ptk;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'nuptk',
        'nrg',
        'k_sumber',
        'nama',
        'tmp_lahir',
        'tgl_lahir',
        'kelamin',
        'golongan',
        'tmt_angkat',
        'k_pegawai',
        'k_kualifikasi',
        'jenjang',
        'instansi_id',
        'sekolah_id',
        'instansi',
        'alamat',
        'k_propinsi',
        'k_kota',
        'k_kecamatan',
        'k_mapel_ukg',
        'passwd',
        'paspor_id',
        'no_hp',
        'email',
        'alt_email',
        'moodle_id',
        'foto',
        'is_kandidat',
        'is_sertifikasi',
        'k_mapel_sertifikasi',
        'is_verval',
        'is_setuju_dinas',
        'token_ujian',
        'max_ukg',
        'nip',
        'is_dapodik',
        'k_jenis_ptk',
        'dapodik_ptk_id',
        'dapodik_nama',
        'kebutuhan_khusus_id',
        'wkt_sinkron',
        'is_aktif',
        'akun_id',
    ];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
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
        'ptk_id',
        'nuptk',
        'nrg',
        'k_sumber',
        'nama',
        'tmp_lahir',
        'tgl_lahir',
        'kelamin',
        'golongan',
        'tmt_angkat',
        'k_pegawai',
        'k_kualifikasi',
        'jenjang',
        'instansi_id',
        'sekolah_id',
        'instansi',
        'alamat',
        'k_propinsi',
        'k_kota',
        'k_kecamatan',
        'k_mapel_ukg',
        'passwd',
        'paspor_id',
        'no_hp',
        'email',
        'alt_email',
        'moodle_id',
        'foto',
        'is_kandidat',
        'is_sertifikasi',
        'k_mapel_sertifikasi',
        'is_verval',
        'is_setuju_dinas',
        'token_ujian',
        'max_ukg',
        'nip',
        'is_dapodik',
        'k_jenis_ptk',
        'dapodik_ptk_id',
        'dapodik_nama',
        'kebutuhan_khusus_id',
        'wkt_sinkron',
        'is_aktif',
        'akun_id',
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
        'ptk_id',
        'nuptk',
        'nrg',
        'k_sumber',
        'nama',
        'tmp_lahir',
        'tgl_lahir',
        'kelamin',
        'golongan',
        'tmt_angkat',
        'k_pegawai',
        'k_kualifikasi',
        'jenjang',
        'instansi_id',
        'sekolah_id',
        'instansi',
        'alamat',
        'k_propinsi',
        'k_kota',
        'k_kecamatan',
        'k_mapel_ukg',
        'passwd',
        'paspor_id',
        'no_hp',
        'email',
        'alt_email',
        'moodle_id',
        'foto',
        'is_kandidat',
        'is_sertifikasi',
        'k_mapel_sertifikasi',
        'is_verval',
        'is_setuju_dinas',
        'token_ujian',
        'max_ukg',
        'nip',
        'is_dapodik',
        'k_jenis_ptk',
        'dapodik_ptk_id',
        'dapodik_nama',
        'kebutuhan_khusus_id',
        'wkt_sinkron',
        'is_aktif',
        'akun_id',
    ];

    /**
     * Get resource validation rules.
     *
     * @param Ptk|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'nuptk'               => ['sometimes', 'nullable', 'string'],
            'nrg'                 => ['sometimes', 'nullable', 'string'],
            'k_sumber'            => ['required', 'integer'],
            'nama'                => ['sometimes', 'nullable', 'string'],
            'tmp_lahir'           => ['sometimes', 'nullable', 'string'],
            'tgl_lahir'           => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'kelamin'             => ['sometimes', 'nullable', 'string'],
            'golongan'            => ['sometimes', 'nullable', 'string'],
            'tmt_angkat'          => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'k_pegawai'           => ['sometimes', 'nullable', 'integer'],
            'k_kualifikasi'       => ['sometimes', 'nullable', 'integer'],
            'jenjang'             => ['sometimes', 'nullable', 'string'],
            'instansi_id'         => ['sometimes', 'nullable', 'integer'],
            'sekolah_id'          => ['required', 'integer'],
            'instansi'            => ['sometimes', 'nullable', 'string'],
            'alamat'              => ['sometimes', 'nullable', 'string'],
            'k_propinsi'          => ['sometimes', 'nullable', 'integer'],
            'k_kota'              => ['sometimes', 'nullable', 'integer'],
            'k_kecamatan'         => ['sometimes', 'nullable', 'integer'],
            'k_mapel_ukg'         => ['sometimes', 'nullable', 'integer'],
            'passwd'              => ['sometimes', 'nullable', 'string'],
            'paspor_id'           => ['sometimes', 'nullable', 'integer'],
            'no_hp'               => ['sometimes', 'nullable', 'string'],
            'email'               => ['sometimes', 'nullable', 'string'],
            'alt_email'           => ['sometimes', 'nullable', 'string'],
            'moodle_id'           => ['sometimes', 'nullable', 'integer'],
            'foto'                => ['sometimes', 'nullable', 'string'],
            'is_kandidat'         => ['sometimes', 'nullable', 'string'],
            'is_sertifikasi'      => ['sometimes', 'nullable', 'string'],
            'k_mapel_sertifikasi' => ['sometimes', 'nullable', 'integer'],
            'is_verval'           => ['required', 'integer'],
            'is_setuju_dinas'     => ['required', 'integer'],
            'token_ujian'         => ['sometimes', 'nullable', 'string'],
            'max_ukg'             => ['sometimes', 'nullable', 'numeric'],
            'nip'                 => ['sometimes', 'nullable', 'string'],
            'is_dapodik'          => ['sometimes', 'nullable', 'integer'],
            'k_jenis_ptk'         => ['sometimes', 'nullable', 'integer'],
            'dapodik_ptk_id'      => ['sometimes', 'nullable', 'string'],
            'dapodik_nama'        => ['sometimes', 'nullable', 'string'],
            'kebutuhan_khusus_id' => ['sometimes', 'nullable', 'integer'],
            'wkt_sinkron'         => ['sometimes', 'nullable', 'datetime'],
            'is_aktif'            => ['sometimes', 'nullable', 'integer'],
            'akun_id'             => ['sometimes', 'nullable', 'string'],
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
            'filter.nuptk'               => ['sometimes', 'string'],
            'filter.nrg'                 => ['sometimes', 'string'],
            'filter.k_sumber'            => ['sometimes', 'integer'],
            'filter.nama'                => ['sometimes', 'string'],
            'filter.tmp_lahir'           => ['sometimes', 'string'],
            'filter.tgl_lahir'           => ['sometimes', 'date_format:Y-m-d'],
            'filter.kelamin'             => ['sometimes', 'string'],
            'filter.golongan'            => ['sometimes', 'string'],
            'filter.tmt_angkat'          => ['sometimes', 'date_format:Y-m-d'],
            'filter.k_pegawai'           => ['sometimes', 'integer'],
            'filter.k_kualifikasi'       => ['sometimes', 'integer'],
            'filter.jenjang'             => ['sometimes', 'string'],
            'filter.instansi_id'         => ['sometimes', 'integer'],
            'filter.sekolah_id'          => ['sometimes', 'integer'],
            'filter.instansi'            => ['sometimes', 'string'],
            'filter.alamat'              => ['sometimes', 'string'],
            'filter.k_propinsi'          => ['sometimes', 'integer'],
            'filter.k_kota'              => ['sometimes', 'integer'],
            'filter.k_kecamatan'         => ['sometimes', 'integer'],
            'filter.k_mapel_ukg'         => ['sometimes', 'integer'],
            'filter.passwd'              => ['sometimes', 'string'],
            'filter.paspor_id'           => ['sometimes', 'integer'],
            'filter.no_hp'               => ['sometimes', 'string'],
            'filter.email'               => ['sometimes', 'string'],
            'filter.alt_email'           => ['sometimes', 'string'],
            'filter.moodle_id'           => ['sometimes', 'integer'],
            'filter.foto'                => ['sometimes', 'string'],
            'filter.is_kandidat'         => ['sometimes', 'string'],
            'filter.is_sertifikasi'      => ['sometimes', 'string'],
            'filter.k_mapel_sertifikasi' => ['sometimes', 'integer'],
            'filter.is_verval'           => ['sometimes', 'integer'],
            'filter.is_setuju_dinas'     => ['sometimes', 'integer'],
            'filter.token_ujian'         => ['sometimes', 'string'],
            'filter.max_ukg'             => ['sometimes', 'numeric'],
            'filter.nip'                 => ['sometimes', 'string'],
            'filter.is_dapodik'          => ['sometimes', 'integer'],
            'filter.k_jenis_ptk'         => ['sometimes', 'integer'],
            'filter.dapodik_ptk_id'      => ['sometimes', 'string'],
            'filter.dapodik_nama'        => ['sometimes', 'string'],
            'filter.kebutuhan_khusus_id' => ['sometimes', 'integer'],
            'filter.wkt_sinkron'         => ['sometimes', 'datetime'],
            'filter.is_aktif'            => ['sometimes', 'integer'],
            'filter.akun_id'             => ['sometimes', 'string'],
            'page.number'                => ['integer', 'min:1'],
            'page.size'                  => ['integer', 'between:1,50'],
        ];
    }
}
