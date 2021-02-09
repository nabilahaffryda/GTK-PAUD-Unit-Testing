<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Ptk
 *
 * @property string $ptk_id
 * @property null|string $nuptk
 * @property null|string $nrg
 * @property int $k_sumber
 * @property null|string $nama
 * @property null|string $tmp_lahir
 * @property null|Carbon $tgl_lahir
 * @property null|string $kelamin
 * @property null|string $golongan
 * @property null|Carbon $tmt_angkat
 * @property null|int $k_pegawai
 * @property null|int $k_kualifikasi
 * @property null|string $jenjang
 * @property null|int $instansi_id
 * @property int $sekolah_id
 * @property null|string $instansi
 * @property null|string $alamat
 * @property null|int $k_propinsi
 * @property null|int $k_kota
 * @property null|int $k_kecamatan
 * @property null|int $k_mapel_ukg
 * @property null|string $passwd
 * @property null|int $paspor_id
 * @property null|string $no_hp
 * @property null|string $email
 * @property null|string $alt_email
 * @property null|int $moodle_id
 * @property null|string $foto
 * @property null|string $is_kandidat
 * @property null|string $is_sertifikasi
 * @property null|int $k_mapel_sertifikasi
 * @property int $is_verval
 * @property int $is_setuju_dinas
 * @property null|string $token_ujian
 * @property null|float $max_ukg
 * @property null|string $nip
 * @property null|int $is_dapodik
 * @property null|int $k_jenis_ptk
 * @property null|string $dapodik_ptk_id
 * @property null|string $dapodik_nama
 * @property null|int $kebutuhan_khusus_id
 * @property null|Carbon $wkt_sinkron
 * @property null|int $is_aktif
 * @property null|string $akun_id
 *
 * @method static Builder|Ptk wherePtkId($value)
 * @method static Builder|Ptk whereNuptk($value)
 * @method static Builder|Ptk whereNrg($value)
 * @method static Builder|Ptk whereKSumber($value)
 * @method static Builder|Ptk whereNama($value)
 * @method static Builder|Ptk whereTmpLahir($value)
 * @method static Builder|Ptk whereTglLahir($value)
 * @method static Builder|Ptk whereKelamin($value)
 * @method static Builder|Ptk whereGolongan($value)
 * @method static Builder|Ptk whereTmtAngkat($value)
 * @method static Builder|Ptk whereKPegawai($value)
 * @method static Builder|Ptk whereKKualifikasi($value)
 * @method static Builder|Ptk whereJenjang($value)
 * @method static Builder|Ptk whereInstansiId($value)
 * @method static Builder|Ptk whereSekolahId($value)
 * @method static Builder|Ptk whereInstansi($value)
 * @method static Builder|Ptk whereAlamat($value)
 * @method static Builder|Ptk whereKPropinsi($value)
 * @method static Builder|Ptk whereKKota($value)
 * @method static Builder|Ptk whereKKecamatan($value)
 * @method static Builder|Ptk whereKMapelUkg($value)
 * @method static Builder|Ptk wherePasswd($value)
 * @method static Builder|Ptk wherePasporId($value)
 * @method static Builder|Ptk whereNoHp($value)
 * @method static Builder|Ptk whereEmail($value)
 * @method static Builder|Ptk whereAltEmail($value)
 * @method static Builder|Ptk whereMoodleId($value)
 * @method static Builder|Ptk whereFoto($value)
 * @method static Builder|Ptk whereIsKandidat($value)
 * @method static Builder|Ptk whereIsSertifikasi($value)
 * @method static Builder|Ptk whereKMapelSertifikasi($value)
 * @method static Builder|Ptk whereIsVerval($value)
 * @method static Builder|Ptk whereIsSetujuDinas($value)
 * @method static Builder|Ptk whereTokenUjian($value)
 * @method static Builder|Ptk whereMaxUkg($value)
 * @method static Builder|Ptk whereNip($value)
 * @method static Builder|Ptk whereIsDapodik($value)
 * @method static Builder|Ptk whereKJenisPtk($value)
 * @method static Builder|Ptk whereDapodikPtkId($value)
 * @method static Builder|Ptk whereDapodikNama($value)
 * @method static Builder|Ptk whereKebutuhanKhususId($value)
 * @method static Builder|Ptk whereWktSinkron($value)
 * @method static Builder|Ptk whereIsAktif($value)
 * @method static Builder|Ptk whereAkunId($value)
 */
class Ptk extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ptk';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ptk_id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ptk_id'              => 'int',
        'nuptk'               => 'string',
        'nrg'                 => 'string',
        'k_sumber'            => 'int',
        'nama'                => 'string',
        'tmp_lahir'           => 'string',
        'kelamin'             => 'string',
        'golongan'            => 'string',
        'k_pegawai'           => 'int',
        'k_kualifikasi'       => 'int',
        'jenjang'             => 'string',
        'instansi_id'         => 'int',
        'sekolah_id'          => 'int',
        'instansi'            => 'string',
        'alamat'              => 'string',
        'k_propinsi'          => 'int',
        'k_kota'              => 'int',
        'k_kecamatan'         => 'int',
        'k_mapel_ukg'         => 'int',
        'passwd'              => 'string',
        'paspor_id'           => 'int',
        'no_hp'               => 'string',
        'email'               => 'string',
        'alt_email'           => 'string',
        'moodle_id'           => 'int',
        'foto'                => 'string',
        'is_kandidat'         => 'string',
        'is_sertifikasi'      => 'string',
        'k_mapel_sertifikasi' => 'int',
        'is_verval'           => 'int',
        'is_setuju_dinas'     => 'int',
        'token_ujian'         => 'string',
        'max_ukg'             => 'float',
        'nip'                 => 'string',
        'is_dapodik'          => 'int',
        'k_jenis_ptk'         => 'int',
        'dapodik_ptk_id'      => 'string',
        'dapodik_nama'        => 'string',
        'kebutuhan_khusus_id' => 'int',
        'is_aktif'            => 'int',
        'akun_id'             => 'string',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nuptk',
        'nrg',
        'k_sumber',
        'nama',
        'tmp_lahir',
        'tgl_lahir',
        'kelamin',
        'golongan',
        'tmt_angkat',
        'k_pegawai',
        'k_kualifikasi',
        'jenjang',
        'instansi_id',
        'sekolah_id',
        'instansi',
        'alamat',
        'k_propinsi',
        'k_kota',
        'k_kecamatan',
        'k_mapel_ukg',
        'passwd',
        'paspor_id',
        'no_hp',
        'email',
        'alt_email',
        'moodle_id',
        'foto',
        'is_kandidat',
        'is_sertifikasi',
        'k_mapel_sertifikasi',
        'is_verval',
        'is_setuju_dinas',
        'token_ujian',
        'max_ukg',
        'nip',
        'is_dapodik',
        'k_jenis_ptk',
        'dapodik_ptk_id',
        'dapodik_nama',
        'kebutuhan_khusus_id',
        'wkt_sinkron',
        'is_aktif',
        'akun_id',
    ];
}
