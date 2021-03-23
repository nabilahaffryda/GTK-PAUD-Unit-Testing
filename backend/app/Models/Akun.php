<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Akun
 *
 * @property string $akun_id
 * @property null|string $nip
 * @property string $nama
 * @property null|string $passwd
 * @property null|string $tmp_lahir
 * @property null|Carbon $tgl_lahir
 * @property null|string $no_telpon
 * @property null|string $no_hp
 * @property string $email
 * @property null|string $kelamin
 * @property null|string $foto
 * @property null|int $k_golongan
 * @property null|int $moodle_id
 * @property null|int $paspor_id
 * @property null|string $token
 * @property null|string $jabatan
 * @property null|string $instansi_asal
 * @property null|int $instansi_k_propinsi
 * @property null|int $instansi_k_kota
 * @property null|string $instansi_alamat
 * @property null|string $instansi_kodepos
 * @property null|string $alamat
 * @property null|int $k_propinsi
 * @property null|int $k_kota
 * @property null|string $kodepos
 * @property null|string $nik
 * @property null|string $npwp
 * @property null|string $rekening_nama
 * @property null|string $rekening_bank
 * @property null|string $rekening_cabang
 * @property null|string $rekening_nomor
 * @property null|int $k_jabatan_guru
 * @property null|int $k_jabatan_dosen_ppg
 * @property null|string $gelar_depan
 * @property null|string $gelar_belakang
 * @property string $is_aktif
 * @property null|string $admin_id
 *
 * @property-read Collection|AkunInstansi[] $akunInstansis
 *
 * @method static Builder|Akun whereAkunId($value)
 * @method static Builder|Akun whereNip($value)
 * @method static Builder|Akun whereNama($value)
 * @method static Builder|Akun wherePasswd($value)
 * @method static Builder|Akun whereTmpLahir($value)
 * @method static Builder|Akun whereTglLahir($value)
 * @method static Builder|Akun whereNoTelpon($value)
 * @method static Builder|Akun whereNoHp($value)
 * @method static Builder|Akun whereEmail($value)
 * @method static Builder|Akun whereKelamin($value)
 * @method static Builder|Akun whereFoto($value)
 * @method static Builder|Akun whereKGolongan($value)
 * @method static Builder|Akun whereMoodleId($value)
 * @method static Builder|Akun wherePasporId($value)
 * @method static Builder|Akun whereToken($value)
 * @method static Builder|Akun whereJabatan($value)
 * @method static Builder|Akun whereInstansiAsal($value)
 * @method static Builder|Akun whereInstansiKPropinsi($value)
 * @method static Builder|Akun whereInstansiKKota($value)
 * @method static Builder|Akun whereInstansiAlamat($value)
 * @method static Builder|Akun whereInstansiKodepos($value)
 * @method static Builder|Akun whereAlamat($value)
 * @method static Builder|Akun whereKPropinsi($value)
 * @method static Builder|Akun whereKKota($value)
 * @method static Builder|Akun whereKodepos($value)
 * @method static Builder|Akun whereNik($value)
 * @method static Builder|Akun whereNpwp($value)
 * @method static Builder|Akun whereRekeningNama($value)
 * @method static Builder|Akun whereRekeningBank($value)
 * @method static Builder|Akun whereRekeningCabang($value)
 * @method static Builder|Akun whereRekeningNomor($value)
 * @method static Builder|Akun whereKJabatanGuru($value)
 * @method static Builder|Akun whereKJabatanDosenPpg($value)
 * @method static Builder|Akun whereGelarDepan($value)
 * @method static Builder|Akun whereGelarBelakang($value)
 * @method static Builder|Akun whereIsAktif($value)
 * @method static Builder|Akun whereAdminId($value)
 *
 * @method static Akun findOrFail($id, $columns = [])
 */
class Akun extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'akun';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'akun_id';

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
        'akun_id'             => 'int',
        'nip'                 => 'string',
        'nama'                => 'string',
        'passwd'              => 'string',
        'tmp_lahir'           => 'string',
        'no_telpon'           => 'string',
        'no_hp'               => 'string',
        'email'               => 'string',
        'kelamin'             => 'string',
        'foto'                => 'string',
        'k_golongan'          => 'int',
        'moodle_id'           => 'int',
        'paspor_id'           => 'int',
        'token'               => 'string',
        'jabatan'             => 'string',
        'instansi_asal'       => 'string',
        'instansi_k_propinsi' => 'int',
        'instansi_k_kota'     => 'int',
        'instansi_alamat'     => 'string',
        'instansi_kodepos'    => 'string',
        'alamat'              => 'string',
        'k_propinsi'          => 'int',
        'k_kota'              => 'int',
        'kodepos'             => 'string',
        'nik'                 => 'string',
        'npwp'                => 'string',
        'rekening_nama'       => 'string',
        'rekening_bank'       => 'string',
        'rekening_cabang'     => 'string',
        'rekening_nomor'      => 'string',
        'k_jabatan_guru'      => 'int',
        'k_jabatan_dosen_ppg' => 'int',
        'gelar_depan'         => 'string',
        'gelar_belakang'      => 'string',
        'is_aktif'            => 'string',
        'admin_id'            => 'string',
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
        'akun_id',
        'nip',
        'nama',
        'passwd',
        'tmp_lahir',
        'tgl_lahir',
        'no_telpon',
        'no_hp',
        'email',
        'kelamin',
        'foto',
        'k_golongan',
        'moodle_id',
        'paspor_id',
        'token',
        'jabatan',
        'instansi_asal',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_alamat',
        'instansi_kodepos',
        'alamat',
        'k_propinsi',
        'k_kota',
        'kodepos',
        'nik',
        'npwp',
        'rekening_nama',
        'rekening_bank',
        'rekening_cabang',
        'rekening_nomor',
        'k_jabatan_guru',
        'k_jabatan_dosen_ppg',
        'gelar_depan',
        'gelar_belakang',
        'is_aktif',
        'admin_id',
    ];

    /**
     * @return HasMany|Builder|AkunInstansi
     */
    public function akunInstansis()
    {
        return $this->hasMany('App\Models\AkunInstansi', 'akun_id', 'akun_id');
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'paspor_id';
    }
}
