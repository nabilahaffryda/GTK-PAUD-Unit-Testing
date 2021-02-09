<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Instansi
 *
 * @property int $instansi_id
 * @property null|int $k_jenis_instansi
 * @property string $nama
 * @property null|string $alamat
 * @property null|int $k_propinsi
 * @property null|int $k_kota
 * @property null|int $moodle_id
 * @property null|string $kode_penyelenggara
 * @property null|string $email
 * @property null|string $no_telpon
 * @property null|string $nama_pejabat
 * @property null|string $nip_pejabat
 * @property null|string $nama_pkp
 * @property null|string $kode_pkp
 * @property null|string $anggaran_pkp
 * @property null|string $data_pkp
 * @property null|string $kode_rkakl
 * @property null|string $nama_rkakl
 *
 * @method static Builder|Instansi whereInstansiId($value)
 * @method static Builder|Instansi whereKJenisInstansi($value)
 * @method static Builder|Instansi whereNama($value)
 * @method static Builder|Instansi whereAlamat($value)
 * @method static Builder|Instansi whereKPropinsi($value)
 * @method static Builder|Instansi whereKKota($value)
 * @method static Builder|Instansi whereMoodleId($value)
 * @method static Builder|Instansi whereKodePenyelenggara($value)
 * @method static Builder|Instansi whereEmail($value)
 * @method static Builder|Instansi whereNoTelpon($value)
 * @method static Builder|Instansi whereNamaPejabat($value)
 * @method static Builder|Instansi whereNipPejabat($value)
 * @method static Builder|Instansi whereNamaPkp($value)
 * @method static Builder|Instansi whereKodePkp($value)
 * @method static Builder|Instansi whereAnggaranPkp($value)
 * @method static Builder|Instansi whereDataPkp($value)
 * @method static Builder|Instansi whereKodeRkakl($value)
 * @method static Builder|Instansi whereNamaRkakl($value)
 */
class Instansi extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'instansi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'instansi_id';

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
        'instansi_id'        => 'int',
        'k_jenis_instansi'   => 'int',
        'nama'               => 'string',
        'alamat'             => 'string',
        'k_propinsi'         => 'int',
        'k_kota'             => 'int',
        'moodle_id'          => 'int',
        'kode_penyelenggara' => 'string',
        'email'              => 'string',
        'no_telpon'          => 'string',
        'nama_pejabat'       => 'string',
        'nip_pejabat'        => 'string',
        'nama_pkp'           => 'string',
        'kode_pkp'           => 'string',
        'anggaran_pkp'       => 'string',
        'data_pkp'           => 'string',
        'kode_rkakl'         => 'string',
        'nama_rkakl'         => 'string',
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
        'instansi_id',
        'k_jenis_instansi',
        'nama',
        'alamat',
        'k_propinsi',
        'k_kota',
        'moodle_id',
        'kode_penyelenggara',
        'email',
        'no_telpon',
        'nama_pejabat',
        'nip_pejabat',
        'nama_pkp',
        'kode_pkp',
        'anggaran_pkp',
        'data_pkp',
        'kode_rkakl',
        'nama_rkakl',
    ];
}