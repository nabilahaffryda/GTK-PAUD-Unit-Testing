<?php

namespace App\JsonApi\MJenisInstansis;

use App\Models\MJenisInstansi;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'm_jenis_instansis';

    /**
     * @param MJenisInstansi $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param MJenisInstansi $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'singkat'    => $resource->singkat,
            'keterangan' => $resource->keterangan,
        ];
    }

    /**
     * @param MJenisInstansi $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'instansis' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'm_groups'  => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
