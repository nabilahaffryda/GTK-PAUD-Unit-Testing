<?php

namespace App\JsonApi\PaudAdmins;

use App\Models\PaudAdmin;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'akun_id',
        'k_group',
        'instansi_id',
        'tahun',
        'angkatan',
        'is_aktif',
        'created_at',
        'updated_at',
        'admin_id',
    ];

    /**
     * The include paths a client is allowed to request.
     *
     * @var string[]|null
     *      the allowed paths, an empty array for none allowed, or null to allow all paths.
     */
    protected $allowedIncludePaths = [
        'akun',
        'instansi',
        'm_group',
    ];

    /**
     * The sort field names a client is allowed send.
     *
     * @var string[]|null
     *      the allowed fields, an empty array for none allowed, or null to allow all fields.
     */
    protected $allowedSortParameters = [
        'paud_admin_id',
        'akun_id',
        'k_group',
        'instansi_id',
        'tahun',
        'angkatan',
        'is_aktif',
        'created_at',
        'updated_at',
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
        'paud_admin_id',
        'akun_id',
        'k_group',
        'instansi_id',
        'tahun',
        'angkatan',
        'is_aktif',
        'created_at',
        'updated_at',
        'admin_id',
    ];

    /**
     * Get resource validation rules.
     *
     * @param PaudAdmin|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'akun_id'     => ['sometimes', 'nullable', 'string'],
            'k_group'     => ['sometimes', 'nullable', 'integer'],
            'instansi_id' => ['sometimes', 'nullable', 'integer'],
            'tahun'       => ['sometimes', 'nullable', 'integer'],
            'angkatan'    => ['sometimes', 'nullable', 'integer'],
            'is_aktif'    => ['required', 'integer'],
            'created_at'  => ['required', 'datetime'],
            'updated_at'  => ['required', 'datetime'],
            'admin_id'    => ['sometimes', 'nullable', 'string'],
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
            'filter.akun_id'     => ['sometimes', 'string'],
            'filter.k_group'     => ['sometimes', 'integer'],
            'filter.instansi_id' => ['sometimes', 'integer'],
            'filter.tahun'       => ['sometimes', 'integer'],
            'filter.angkatan'    => ['sometimes', 'integer'],
            'filter.is_aktif'    => ['sometimes', 'integer'],
            'filter.created_at'  => ['sometimes', 'datetime'],
            'filter.updated_at'  => ['sometimes', 'datetime'],
            'filter.admin_id'    => ['sometimes', 'string'],
            'page.number'        => ['integer', 'min:1'],
            'page.size'          => ['integer', 'between:1,50'],
        ];
    }
}
