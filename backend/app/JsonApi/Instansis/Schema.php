<?php

namespace App\JsonApi\Instansis;

use App\Models\Instansi;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'instansis';

    /**
     * @param Instansi $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Instansi $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'k_jenis_instansi'   => $resource->k_jenis_instansi,
            'nama'               => $resource->nama,
            'alamat'             => $resource->alamat,
            'k_propinsi'         => $resource->k_propinsi,
            'k_kota'             => $resource->k_kota,
            'moodle_id'          => $resource->moodle_id,
            'kode_penyelenggara' => $resource->kode_penyelenggara,
            'email'              => $resource->email,
            'no_telpon'          => $resource->no_telpon,
            'nama_pejabat'       => $resource->nama_pejabat,
            'nip_pejabat'        => $resource->nip_pejabat,
            'nama_pkp'           => $resource->nama_pkp,
            'kode_pkp'           => $resource->kode_pkp,
            'anggaran_pkp'       => $resource->anggaran_pkp,
            'data_pkp'           => $resource->data_pkp,
            'kode_rkakl'         => $resource->kode_rkakl,
            'nama_rkakl'         => $resource->nama_rkakl,
        ];
    }

    /**
     * @param Instansi $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'akun_instansis'         => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'm_jenis_instansi'       => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_jenis_instansi']),
                self::DATA         => function () use ($resource) {
                    return $resource->mJenisInstansi;
                },
            ],
            'm_kota'                 => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_kota']),
                self::DATA         => function () use ($resource) {
                    return $resource->mKota;
                },
            ],
            'm_propinsi'             => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_propinsi']),
                self::DATA         => function () use ($resource) {
                    return $resource->mPropinsi;
                },
            ],
            'paud_admins'            => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'paud_instansi_berkases' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'paud_instansis'         => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
