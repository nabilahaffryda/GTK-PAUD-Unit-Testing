<?php

namespace App\JsonApi\Akuns;

use App\Models\Akun;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{
    /**
     * @var string
     */
    protected $resourceType = 'akuns';

    /**
     * @param Akun $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string)$resource->getRouteKey();
    }

    /**
     * @param Akun $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'nip'                 => $resource->nip,
            'nama'                => $resource->nama,
            'passwd'              => $resource->passwd,
            'tmp_lahir'           => $resource->tmp_lahir,
            'tgl_lahir'           => $resource->tgl_lahir,
            'no_telpon'           => $resource->no_telpon,
            'no_hp'               => $resource->no_hp,
            'email'               => $resource->email,
            'kelamin'             => $resource->kelamin,
            'foto'                => $resource->foto,
            'k_golongan'          => $resource->k_golongan,
            'moodle_id'           => $resource->moodle_id,
            'paspor_id'           => $resource->paspor_id,
            'token'               => $resource->token,
            'jabatan'             => $resource->jabatan,
            'instansi_asal'       => $resource->instansi_asal,
            'instansi_k_propinsi' => $resource->instansi_k_propinsi,
            'instansi_k_kota'     => $resource->instansi_k_kota,
            'instansi_alamat'     => $resource->instansi_alamat,
            'instansi_kodepos'    => $resource->instansi_kodepos,
            'alamat'              => $resource->alamat,
            'k_propinsi'          => $resource->k_propinsi,
            'k_kota'              => $resource->k_kota,
            'kodepos'             => $resource->kodepos,
            'nik'                 => $resource->nik,
            'npwp'                => $resource->npwp,
            'rekening_nama'       => $resource->rekening_nama,
            'rekening_bank'       => $resource->rekening_bank,
            'rekening_cabang'     => $resource->rekening_cabang,
            'rekening_nomor'      => $resource->rekening_nomor,
            'k_jabatan_guru'      => $resource->k_jabatan_guru,
            'k_jabatan_dosen_ppg' => $resource->k_jabatan_dosen_ppg,
            'gelar_depan'         => $resource->gelar_depan,
            'gelar_belakang'      => $resource->gelar_belakang,
            'is_aktif'            => $resource->is_aktif,
            'admin_id'            => $resource->admin_id,
        ];
    }

    /**
     * @param Akun $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array[]
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'akun_instansis' => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
            'paud_admins'    => [
                self::SHOW_SELF    => true,
                self::SHOW_RELATED => true,
            ],
        ];
    }
}
