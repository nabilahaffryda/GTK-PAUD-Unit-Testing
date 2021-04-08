<?php

namespace App\JsonApi\PaudAkseses;

use App\Models\PaudAkses;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'paud_akseses';

    /**
     * @param PaudAkses $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param PaudAkses $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'akses'    => $resource->akses,
            'label'    => $resource->label,
            'guard'    => $resource->guard,
            'is_aktif' => $resource->is_aktif,
        ];
    }

    /**
     * @param PaudAkses $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'paud_group_akseses' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
