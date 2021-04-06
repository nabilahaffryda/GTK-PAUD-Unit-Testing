<?php

namespace App\JsonApi\PaudInstansis;

use App\Models\PaudInstansi;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'paud_instansis';

    /**
     * @param PaudInstansi $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param PaudInstansi $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'instansi_id'           => $resource->instansi_id,
            'tahun'                 => $resource->tahun,
            'angkatan'              => $resource->angkatan,
            'k_verval_paud'         => $resource->k_verval_paud,
            'kodepos'               => $resource->kodepos,
            'nama_penanggung_jawab' => $resource->nama_penanggung_jawab,
            'telp_penanggung_jawab' => $resource->telp_penanggung_jawab,
            'nama_sekretaris'       => $resource->nama_sekretaris,
            'telp_sekretaris'       => $resource->telp_sekretaris,
            'nama_bendahara'        => $resource->nama_bendahara,
            'telp_bendahara'        => $resource->telp_bendahara,
            'diklat'                => $resource->diklat,
            'created_at'            => $resource->created_at,
            'updated_at'            => $resource->updated_at,
        ];
    }

    /**
     * @param PaudInstansi $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'instansi'               => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['instansi']),
                self::DATA         => function () use ($resource) {
                    return $resource->instansi;
                },
            ],
            'm_verval_paud'          => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_verval_paud']),
                self::DATA         => function () use ($resource) {
                    return $resource->mVervalPaud;
                },
            ],
            'paud_instansi_berkases' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
