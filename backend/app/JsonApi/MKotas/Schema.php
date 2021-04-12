<?php

namespace App\JsonApi\MKotas;

use App\Models\MKota;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'm_kotas';

    /**
     * @param MKota $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param MKota $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'k_propinsi' => $resource->k_propinsi,
            'singkat'    => $resource->singkat,
            'keterangan' => $resource->keterangan,
            'kode_ukg'   => $resource->kode_ukg,
            'is_kota'    => $resource->is_kota,
            'is_ibukota' => $resource->is_ibukota,
            'is_3t'      => $resource->is_3t,
            'timezone'   => $resource->timezone,
            'is_aktif'   => $resource->is_aktif,
        ];
    }

    /**
     * @param MKota $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'akuns'          => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'instansi_akuns' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'instansis'      => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'm_propinsi'     => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_propinsi']),
                self::DATA         => function () use ($resource) {
                    return $resource->mPropinsi;
                },
            ],
            'ptks'           => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
