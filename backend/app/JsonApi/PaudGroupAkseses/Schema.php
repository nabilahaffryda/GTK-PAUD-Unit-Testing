<?php

namespace App\JsonApi\PaudGroupAkseses;

use App\Models\PaudGroupAkses;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'paud_group_akseses';

    /**
     * @param PaudGroupAkses $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param PaudGroupAkses $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'paud_akses_id' => $resource->paud_akses_id,
            'k_group'       => $resource->k_group,
            'is_aktif'      => $resource->is_aktif,
        ];
    }

    /**
     * @param PaudGroupAkses $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'm_group'    => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_group']),
                self::DATA         => function () use ($resource) {
                    return $resource->mGroup;
                },
            ],
            'paud_akses' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['paud_akses']),
                self::DATA         => function () use ($resource) {
                    return $resource->paudAkses;
                },
            ],
        ];
    }
}
