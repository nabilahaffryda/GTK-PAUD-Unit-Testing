<?php

namespace App\JsonApi\MPropinsis;

use App\Models\MPropinsi;
use CloudCreativity\LaravelJsonApi\Validation\AbstractValidators;

class Validators extends AbstractValidators
{
    /**
     * Custom attributes for the resource validator.
     *
     * @var array
     */
    protected $attributes = [
        'singkat',
        'keterangan',
        'kode_ukg',
        'timezone',
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
        'k_propinsi',
        'singkat',
        'keterangan',
        'kode_ukg',
        'timezone',
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
        'k_propinsi',
        'singkat',
        'keterangan',
        'kode_ukg',
        'timezone',
    ];

    /**
     * Get resource validation rules.
     *
     * @param MPropinsi|null $record
     *      the record being updated, or null if creating a resource.
     * @param array $data
     *      the data being validated
     * @return array
     */
    protected function rules($record, array $data): array
    {
        return [
            'singkat'    => ['sometimes', 'nullable', 'string'],
            'keterangan' => ['sometimes', 'nullable', 'string'],
            'kode_ukg'   => ['sometimes', 'nullable', 'string'],
            'timezone'   => ['sometimes', 'nullable', 'integer'],
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
            'filter.singkat'    => ['sometimes', 'string'],
            'filter.keterangan' => ['sometimes', 'string'],
            'filter.kode_ukg'   => ['sometimes', 'string'],
            'filter.timezone'   => ['sometimes', 'integer'],
            'page.number'       => ['integer', 'min:1'],
            'page.size'         => ['integer', 'between:1,50'],
        ];
    }
}
