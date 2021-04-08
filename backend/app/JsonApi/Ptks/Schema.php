<?php

namespace App\JsonApi\Ptks;

use App\Models\Ptk;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'ptks';

    /**
     * @param Ptk $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Ptk $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'nuptk'               => $resource->nuptk,
            'nrg'                 => $resource->nrg,
            'k_sumber'            => $resource->k_sumber,
            'nama'                => $resource->nama,
            'tmp_lahir'           => $resource->tmp_lahir,
            'tgl_lahir'           => $resource->tgl_lahir,
            'kelamin'             => $resource->kelamin,
            'golongan'            => $resource->golongan,
            'tmt_angkat'          => $resource->tmt_angkat,
            'k_pegawai'           => $resource->k_pegawai,
            'k_kualifikasi'       => $resource->k_kualifikasi,
            'jenjang'             => $resource->jenjang,
            'instansi_id'         => $resource->instansi_id,
            'sekolah_id'          => $resource->sekolah_id,
            'instansi'            => $resource->instansi,
            'alamat'              => $resource->alamat,
            'k_propinsi'          => $resource->k_propinsi,
            'k_kota'              => $resource->k_kota,
            'k_kecamatan'         => $resource->k_kecamatan,
            'k_mapel_ukg'         => $resource->k_mapel_ukg,
            'passwd'              => $resource->passwd,
            'paspor_id'           => $resource->paspor_id,
            'no_hp'               => $resource->no_hp,
            'email'               => $resource->email,
            'alt_email'           => $resource->alt_email,
            'moodle_id'           => $resource->moodle_id,
            'foto'                => $resource->foto,
            'is_kandidat'         => $resource->is_kandidat,
            'is_sertifikasi'      => $resource->is_sertifikasi,
            'k_mapel_sertifikasi' => $resource->k_mapel_sertifikasi,
            'is_verval'           => $resource->is_verval,
            'is_setuju_dinas'     => $resource->is_setuju_dinas,
            'token_ujian'         => $resource->token_ujian,
            'max_ukg'             => $resource->max_ukg,
            'nip'                 => $resource->nip,
            'is_dapodik'          => $resource->is_dapodik,
            'k_jenis_ptk'         => $resource->k_jenis_ptk,
            'dapodik_ptk_id'      => $resource->dapodik_ptk_id,
            'dapodik_nama'        => $resource->dapodik_nama,
            'kebutuhan_khusus_id' => $resource->kebutuhan_khusus_id,
            'wkt_sinkron'         => $resource->wkt_sinkron,
            'is_aktif'            => $resource->is_aktif,
            'akun_id'             => $resource->akun_id,
        ];
    }

    /**
     * @param Ptk $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'kota'       => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['kota']),
                self::DATA         => function () use ($resource) {
                    return $resource->kota;
                },
            ],
            'm_propinsi' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
                self::SHOW_DATA    => isset($includeRelationships['m_propinsi']),
                self::DATA         => function () use ($resource) {
                    return $resource->mPropinsi;
                },
            ],
        ];
    }
}
