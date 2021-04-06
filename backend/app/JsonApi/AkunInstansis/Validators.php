<?php

namespace App\JsonApi\AkunInstansis;

use App\Models\AkunInstansi;
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
        'token',
        'is_aktif',
        'admin_id',
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
        'akun_instansi_id',
        'akun_id',
        'k_group',
        'instansi_id',
        'token',
        'is_aktif',
        'admin_id',
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
        'akun_instansi_id',
        'akun_id',
        'k_group',
        'instansi_id',
        'token',
        'is_aktif',
        'admin_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get resource validation rules.
     *
     * @param AkunInstansi|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'akun_id'     => ['required', 'string'],
            'k_group'     => ['sometimes', 'nullable', 'integer'],
            'instansi_id' => ['sometimes', 'nullable', 'integer'],
            'token'       => ['sometimes', 'nullable', 'string'],
            'is_aktif'    => ['required', 'string'],
            'admin_id'    => ['sometimes', 'nullable', 'string'],
            'created_at'  => ['sometimes', 'nullable', 'datetime'],
            'updated_at'  => ['sometimes', 'nullable', 'datetime'],
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
            'filter.token'       => ['sometimes', 'string'],
            'filter.is_aktif'    => ['sometimes', 'string'],
            'filter.admin_id'    => ['sometimes', 'string'],
            'filter.created_at'  => ['sometimes', 'datetime'],
            'filter.updated_at'  => ['sometimes', 'datetime'],
            'page.number'        => ['integer', 'min:1'],
            'page.size'          => ['integer', 'between:1,50'],
        ];
    }
}
