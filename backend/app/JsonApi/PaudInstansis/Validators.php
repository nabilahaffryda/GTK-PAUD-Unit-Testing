<?php

namespace App\JsonApi\PaudInstansis;

use App\Models\PaudInstansi;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'instansi_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'kodepos',
        'nama_penanggung_jawab',
        'telp_penanggung_jawab',
        'nama_sekretaris',
        'telp_sekretaris',
        'nama_bendahara',
        'telp_bendahara',
        'diklat',
        'created_at',
        'updated_at',
    ];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'instansi',
        'm_verval_paud',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'kodepos',
        'nama_penanggung_jawab',
        'telp_penanggung_jawab',
        'nama_sekretaris',
        'telp_sekretaris',
        'nama_bendahara',
        'telp_bendahara',
        'diklat',
        'created_at',
        'updated_at',
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
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'kodepos',
        'nama_penanggung_jawab',
        'telp_penanggung_jawab',
        'nama_sekretaris',
        'telp_sekretaris',
        'nama_bendahara',
        'telp_bendahara',
        'diklat',
        'created_at',
        'updated_at',
    ];

    /**
     * Get resource validation rules.
     *
     * @param PaudInstansi|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'instansi_id'           => ['required', 'integer'],
            'tahun'                 => ['sometimes', 'nullable', 'integer'],
            'angkatan'              => ['sometimes', 'nullable', 'integer'],
            'k_verval_paud'         => ['required', 'integer'],
            'kodepos'               => ['sometimes', 'nullable', 'string'],
            'nama_penanggung_jawab' => ['sometimes', 'nullable', 'string'],
            'telp_penanggung_jawab' => ['sometimes', 'nullable', 'string'],
            'nama_sekretaris'       => ['sometimes', 'nullable', 'string'],
            'telp_sekretaris'       => ['sometimes', 'nullable', 'string'],
            'nama_bendahara'        => ['sometimes', 'nullable', 'string'],
            'telp_bendahara'        => ['sometimes', 'nullable', 'string'],
            'diklat'                => ['sometimes', 'nullable', 'string'],
            'created_at'            => ['sometimes', 'nullable', 'datetime'],
            'updated_at'            => ['sometimes', 'nullable', 'datetime'],
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
            'filter.instansi_id'           => ['sometimes', 'integer'],
            'filter.tahun'                 => ['sometimes', 'integer'],
            'filter.angkatan'              => ['sometimes', 'integer'],
            'filter.k_verval_paud'         => ['sometimes', 'integer'],
            'filter.kodepos'               => ['sometimes', 'string'],
            'filter.nama_penanggung_jawab' => ['sometimes', 'string'],
            'filter.telp_penanggung_jawab' => ['sometimes', 'string'],
            'filter.nama_sekretaris'       => ['sometimes', 'string'],
            'filter.telp_sekretaris'       => ['sometimes', 'string'],
            'filter.nama_bendahara'        => ['sometimes', 'string'],
            'filter.telp_bendahara'        => ['sometimes', 'string'],
            'filter.diklat'                => ['sometimes', 'string'],
            'filter.created_at'            => ['sometimes', 'datetime'],
            'filter.updated_at'            => ['sometimes', 'datetime'],
            'page.number'                  => ['integer', 'min:1'],
            'page.size'                    => ['integer', 'between:1,50'],
        ];
    }
}
