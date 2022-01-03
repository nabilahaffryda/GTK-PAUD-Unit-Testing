<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Akun
 * @mixin Eloquent
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
 * @property null|int $instansi_k_kecamatan
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
 * @property int $k_status_email
 * @property null|string $admin_id
 *
 * @property-read null|string $foto_url
 *
 * @property-read MKota $instansiKota
 * @property-read MPropinsi $instansiPropinsi
 * @property-read MGolongan $mGolongan
 * @property-read MKota $mKota
 * @property-read MPropinsi $mPropinsi
 * @property-read Collection|AkunInstansi[] $akunInstansis
 * @property-read Collection|PaudAdmin[] $paudAdmins
 * @property-read Collection|PaudKelasPetugasLuring[] $paudKelasPetugasLurings
 * @property-read Collection|PaudKelasPetugas[] $paudKelasPetugases
 * @property-read Collection|PaudPetugasBerkas[] $paudPetugasBerkases
 * @property-read Collection|PaudPetugasDiklat[] $paudPetugasDiklats
 * @property-read Collection|PaudPetugasPeranBerkas[] $paudPetugasPeranBerkases
 * @property-read Collection|PaudPetugasPeran[] $paudPetugasPerans
 * @property-read Collection|PaudPetugas[] $paudPetugases
 * @property-read Collection|PaudPetugasPeran[] $vervalPaudPetugasPerans
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
 * @method static Builder|Akun whereInstansiKKecamatan($value)
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
 * @method static Builder|Akun whereKStatusEmail($value)
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
        'nip'                  => 'string',
        'nama'                 => 'string',
        'passwd'               => 'string',
        'tmp_lahir'            => 'string',
        'tgl_lahir'            => 'date:Y-m-d',
        'no_telpon'            => 'string',
        'no_hp'                => 'string',
        'email'                => 'string',
        'kelamin'              => 'string',
        'foto'                 => 'string',
        'k_golongan'           => 'int',
        'moodle_id'            => 'int',
        'paspor_id'            => 'int',
        'token'                => 'string',
        'jabatan'              => 'string',
        'instansi_asal'        => 'string',
        'instansi_k_propinsi'  => 'int',
        'instansi_k_kota'      => 'int',
        'instansi_k_kecamatan' => 'int',
        'instansi_alamat'      => 'string',
        'instansi_kodepos'     => 'string',
        'alamat'               => 'string',
        'k_propinsi'           => 'int',
        'k_kota'               => 'int',
        'kodepos'              => 'string',
        'nik'                  => 'string',
        'npwp'                 => 'string',
        'rekening_nama'        => 'string',
        'rekening_bank'        => 'string',
        'rekening_cabang'      => 'string',
        'rekening_nomor'       => 'string',
        'k_jabatan_guru'       => 'int',
        'k_jabatan_dosen_ppg'  => 'int',
        'gelar_depan'          => 'string',
        'gelar_belakang'       => 'string',
        'is_aktif'             => 'string',
        'k_status_email'       => 'int',
        'admin_id'             => 'string',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'foto_url',
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
        'instansi_k_kecamatan',
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
        'k_status_email',
        'admin_id',
    ];

    /**
     * @return HasMany
     */
    public function akunInstansis()
    {
        return $this->hasMany('App\Models\AkunInstansi', 'akun_id', 'akun_id');
    }

    /**
     * @return BelongsTo
     */
    public function instansiKota()
    {
        return $this->belongsTo('App\Models\MKota', 'instansi_k_kota', 'k_kota');
    }

    /**
     * @return BelongsTo
     */
    public function instansiPropinsi()
    {
        return $this->belongsTo('App\Models\MPropinsi', 'instansi_k_propinsi', 'k_propinsi');
    }

    /**
     * @return BelongsTo
     */
    public function mGolongan()
    {
        return $this->belongsTo('App\Models\MGolongan', 'k_golongan', 'k_golongan');
    }

    /**
     * @return BelongsTo
     */
    public function mKota()
    {
        return $this->belongsTo('App\Models\MKota', 'k_kota', 'k_kota');
    }

    /**
     * @return BelongsTo
     */
    public function mPropinsi()
    {
        return $this->belongsTo('App\Models\MPropinsi', 'k_propinsi', 'k_propinsi');
    }

    /**
     * @return HasMany
     */
    public function paudAdmins()
    {
        return $this->hasMany('App\Models\PaudAdmin', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPetugasLurings()
    {
        return $this->hasMany('App\Models\PaudKelasPetugasLuring', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPetugases()
    {
        return $this->hasMany('App\Models\PaudKelasPetugas', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasBerkases()
    {
        return $this->hasMany('App\Models\PaudPetugasBerkas', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasDiklats()
    {
        return $this->hasMany('App\Models\PaudPetugasDiklat', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasPeranBerkases()
    {
        return $this->hasMany('App\Models\PaudPetugasPeranBerkas', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasPerans()
    {
        return $this->hasMany('App\Models\PaudPetugasPeran', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugases()
    {
        return $this->hasMany('App\Models\PaudPetugas', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany
     */
    public function vervalPaudPetugasPerans()
    {
        return $this->hasMany('App\Models\PaudPetugasPeran', 'akun_id_verval', 'akun_id');
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

    public function getFotoUrlAttribute()
    {
        return $this->foto ? sprintf("%s/%s", config('filesystems.disks.akun-foto.url'), $this->foto) : null;
    }
}
