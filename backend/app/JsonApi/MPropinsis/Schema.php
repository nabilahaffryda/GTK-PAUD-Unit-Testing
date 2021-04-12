<?php

namespace App\JsonApi\MPropinsis;

use App\Models\MPropinsi;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'm_propinsis';

    /**
     * @param MPropinsi $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param MPropinsi $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'singkat'    => $resource->singkat,
            'keterangan' => $resource->keterangan,
            'kode_ukg'   => $resource->kode_ukg,
            'timezone'   => $resource->timezone,
        ];
    }

    /**
     * @param MPropinsi $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'akuns'                   => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'instansi_propinsi_akuns' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'instansis'               => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'm_kotas'                 => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'ptks'                    => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
