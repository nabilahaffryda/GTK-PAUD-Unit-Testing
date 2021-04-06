<?php

namespace App\JsonApi\MGroups;

use App\Models\MGroup;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'm_groups';

    /**
     * @param MGroup $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param MGroup $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'singkat'          => $resource->singkat,
            'keterangan'       => $resource->keterangan,
            'k_jenis_instansi' => $resource->k_jenis_instansi,
        ];
    }

    /**
     * @param MGroup $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'akun_instansis'     => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'paud_admins'        => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'paud_group_akseses' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
