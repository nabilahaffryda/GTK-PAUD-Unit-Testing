<?php

namespace App\JsonApi\Instansis;

use App\Models\Instansi;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'k_jenis_instansi',
        'nama',
        'alamat',
        'k_propinsi',
        'k_kota',
        'moodle_id',
        'kode_penyelenggara',
        'email',
        'no_telpon',
        'nama_pejabat',
        'nip_pejabat',
        'nama_pkp',
        'kode_pkp',
        'anggaran_pkp',
        'data_pkp',
        'kode_rkakl',
        'nama_rkakl',
    ];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'instansi_id',
        'k_jenis_instansi',
        'nama',
        'alamat',
        'k_propinsi',
        'k_kota',
        'moodle_id',
        'kode_penyelenggara',
        'email',
        'no_telpon',
        'nama_pejabat',
        'nip_pejabat',
        'nama_pkp',
        'kode_pkp',
        'anggaran_pkp',
        'data_pkp',
        'kode_rkakl',
        'nama_rkakl',
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
        'instansi_id',
        'k_jenis_instansi',
        'nama',
        'alamat',
        'k_propinsi',
        'k_kota',
        'moodle_id',
        'kode_penyelenggara',
        'email',
        'no_telpon',
        'nama_pejabat',
        'nip_pejabat',
        'nama_pkp',
        'kode_pkp',
        'anggaran_pkp',
        'data_pkp',
        'kode_rkakl',
        'nama_rkakl',
    ];

    /**
     * Get resource validation rules.
     *
     * @param Instansi|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'k_jenis_instansi'   => ['sometimes', 'nullable', 'integer'],
            'nama'               => ['required', 'string'],
            'alamat'             => ['sometimes', 'nullable', 'string'],
            'k_propinsi'         => ['sometimes', 'nullable', 'integer'],
            'k_kota'             => ['sometimes', 'nullable', 'integer'],
            'moodle_id'          => ['sometimes', 'nullable', 'integer'],
            'kode_penyelenggara' => ['sometimes', 'nullable', 'string'],
            'email'              => ['sometimes', 'nullable', 'string'],
            'no_telpon'          => ['sometimes', 'nullable', 'string'],
            'nama_pejabat'       => ['sometimes', 'nullable', 'string'],
            'nip_pejabat'        => ['sometimes', 'nullable', 'string'],
            'nama_pkp'           => ['sometimes', 'nullable', 'string'],
            'kode_pkp'           => ['sometimes', 'nullable', 'string'],
            'anggaran_pkp'       => ['sometimes', 'nullable', 'string'],
            'data_pkp'           => ['sometimes', 'nullable', 'string'],
            'kode_rkakl'         => ['sometimes', 'nullable', 'string'],
            'nama_rkakl'         => ['sometimes', 'nullable', 'string'],
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
            'filter.k_jenis_instansi'   => ['sometimes', 'integer'],
            'filter.nama'               => ['sometimes', 'string'],
            'filter.alamat'             => ['sometimes', 'string'],
            'filter.k_propinsi'         => ['sometimes', 'integer'],
            'filter.k_kota'             => ['sometimes', 'integer'],
            'filter.moodle_id'          => ['sometimes', 'integer'],
            'filter.kode_penyelenggara' => ['sometimes', 'string'],
            'filter.email'              => ['sometimes', 'string'],
            'filter.no_telpon'          => ['sometimes', 'string'],
            'filter.nama_pejabat'       => ['sometimes', 'string'],
            'filter.nip_pejabat'        => ['sometimes', 'string'],
            'filter.nama_pkp'           => ['sometimes', 'string'],
            'filter.kode_pkp'           => ['sometimes', 'string'],
            'filter.anggaran_pkp'       => ['sometimes', 'string'],
            'filter.data_pkp'           => ['sometimes', 'string'],
            'filter.kode_rkakl'         => ['sometimes', 'string'],
            'filter.nama_rkakl'         => ['sometimes', 'string'],
            'page.number'               => ['integer', 'min:1'],
            'page.size'                 => ['integer', 'between:1,50'],
        ];
    }
}
