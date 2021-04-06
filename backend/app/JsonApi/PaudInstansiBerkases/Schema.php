<?php

namespace App\JsonApi\PaudInstansiBerkas;

use App\Models\PaudInstansiBerkas;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'paud_instansi_berkases';

    /**
     * @param PaudInstansiBerkas $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param PaudInstansiBerkas $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'paud_instansi_id' => $resource->paud_instansi_id,
            'instansi_id'      => $resource->instansi_id,
            'tahun'            => $resource->tahun,
            'angkatan'         => $resource->angkatan,
            'k_berkas_paud'    => $resource->k_berkas_paud,
            'nama'             => $resource->nama,
            'file'             => $resource->file,
            'diklat'           => $resource->diklat,
            'created_at'       => $resource->created_at,
            'updated_at'       => $resource->updated_at,
        ];
    }

    /**
     * @param PaudInstansiBerkas $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'instansi'      => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['instansi']),
                self::DATA         => function () use ($resource) {
                    return $resource->instansi;
                },
            ],
            'm_berkas_paud' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_berkas_paud']),
                self::DATA         => function () use ($resource) {
                    return $resource->mBerkasPaud;
                },
            ],
            'paud_instansi' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['paud_instansi']),
                self::DATA         => function () use ($resource) {
                    return $resource->paudInstansi;
                },
            ],
        ];
    }
}
