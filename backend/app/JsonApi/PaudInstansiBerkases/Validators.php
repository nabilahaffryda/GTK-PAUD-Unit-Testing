<?php

namespace App\JsonApi\PaudInstansiBerkas;

use App\Models\PaudInstansiBerkas;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_berkas_paud',
        'nama',
        'file',
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
        'm_berkas_paud',
        'paud_instansi',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'paud_instansi_berkas_id',
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_berkas_paud',
        'nama',
        'file',
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
        'paud_instansi_berkas_id',
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_berkas_paud',
        'nama',
        'file',
        'diklat',
        'created_at',
        'updated_at',
    ];

    /**
     * Get resource validation rules.
     *
     * @param PaudInstansiBerkas|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'paud_instansi_id' => ['required', 'integer'],
            'instansi_id'      => ['sometimes', 'nullable', 'integer'],
            'tahun'            => ['sometimes', 'nullable', 'integer'],
            'angkatan'         => ['sometimes', 'nullable', 'integer'],
            'k_berkas_paud'    => ['required', 'integer'],
            'nama'             => ['sometimes', 'nullable', 'string'],
            'file'             => ['sometimes', 'nullable', 'string'],
            'diklat'           => ['sometimes', 'nullable', 'string'],
            'created_at'       => ['sometimes', 'nullable', 'datetime'],
            'updated_at'       => ['sometimes', 'nullable', 'datetime'],
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
            'filter.paud_instansi_id' => ['sometimes', 'integer'],
            'filter.instansi_id'      => ['sometimes', 'integer'],
            'filter.tahun'            => ['sometimes', 'integer'],
            'filter.angkatan'         => ['sometimes', 'integer'],
            'filter.k_berkas_paud'    => ['sometimes', 'integer'],
            'filter.nama'             => ['sometimes', 'string'],
            'filter.file'             => ['sometimes', 'string'],
            'filter.diklat'           => ['sometimes', 'string'],
            'filter.created_at'       => ['sometimes', 'datetime'],
            'filter.updated_at'       => ['sometimes', 'datetime'],
            'page.number'             => ['integer', 'min:1'],
            'page.size'               => ['integer', 'between:1,50'],
        ];
    }
}
