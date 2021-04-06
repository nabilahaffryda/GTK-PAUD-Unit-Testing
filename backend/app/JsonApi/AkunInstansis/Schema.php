<?php

namespace App\JsonApi\AkunInstansis;

use App\Models\AkunInstansi;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'akun_instansis';

    /**
     * @param AkunInstansi $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param AkunInstansi $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'akun_id'     => $resource->akun_id,
            'k_group'     => $resource->k_group,
            'instansi_id' => $resource->instansi_id,
            'token'       => $resource->token,
            'is_aktif'    => $resource->is_aktif,
            'admin_id'    => $resource->admin_id,
            'created_at'  => $resource->created_at,
            'updated_at'  => $resource->updated_at,
        ];
    }

    /**
     * @param AkunInstansi $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'akun'     => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['akun']),
                self::DATA         => function () use ($resource) {
                    return $resource->akun;
                },
            ],
            'instansi' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['instansi']),
                self::DATA         => function () use ($resource) {
                    return $resource->instansi;
                },
            ],
            'm_group'  => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_group']),
                self::DATA         => function () use ($resource) {
                    return $resource->mGroup;
                },
            ],
        ];
    }
}
