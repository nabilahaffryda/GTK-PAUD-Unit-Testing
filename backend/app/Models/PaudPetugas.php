<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudPetugas
 *
 * @property int $paud_petugas_id
 * @property null|string $akun_id
 * @property null|int $instansi_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_petugas_paud
 * @property null|string $lulusan
 * @property null|string $prodi
 * @property null|int $k_kualifikasi
 * @property null|int $k_kecamatan
 * @property null|int $k_kelurahan
 * @property null|string $instansi_nama
 * @property null|string $instansi_jabatan
 * @property null|string $instansi_alamat
 * @property null|int $instansi_k_propinsi
 * @property null|int $instansi_k_kota
 * @property null|string $instansi_kodepos
 * @property null|array $data_akun
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|int $is_inti
 * @property null|int $is_refreshment
 * @property null|int $is_pengimbasan
 * @property null|int $k_unsur_pengajar_paud
 *
 * @property-read Akun $akun
 * @property-read Instansi $instansi
 * @property-read MKota $instansiKota
 * @property-read MPropinsi $instansiPropinsi
 * @property-read MPetugasPaud $mPetugasPaud
 * @property-read MUnsurPengajarPaud $mUnsurPengajarPaud
 * @property-read Collection|PaudPetugasBerkas[] $paudPetugasBerkases
 * @property-read Collection|PaudPetugasDiklat[] $paudPetugasDiklats
 * @property-read Collection|PaudPetugasPeran[] $paudPetugasPerans
 * @property-read Collection|PaudPetugasPeranBerkas[] $peranPaudPetugasPeranBerkases
 *
 * @method static Builder|PaudPetugas wherePaudPetugasId($value)
 * @method static Builder|PaudPetugas whereAkunId($value)
 * @method static Builder|PaudPetugas whereInstansiId($value)
 * @method static Builder|PaudPetugas whereTahun($value)
 * @method static Builder|PaudPetugas whereAngkatan($value)
 * @method static Builder|PaudPetugas whereKPetugasPaud($value)
 * @method static Builder|PaudPetugas whereLulusan($value)
 * @method static Builder|PaudPetugas whereProdi($value)
 * @method static Builder|PaudPetugas whereKKualifikasi($value)
 * @method static Builder|PaudPetugas whereKKecamatan($value)
 * @method static Builder|PaudPetugas whereKKelurahan($value)
 * @method static Builder|PaudPetugas whereInstansiNama($value)
 * @method static Builder|PaudPetugas whereInstansiJabatan($value)
 * @method static Builder|PaudPetugas whereInstansiAlamat($value)
 * @method static Builder|PaudPetugas whereInstansiKPropinsi($value)
 * @method static Builder|PaudPetugas whereInstansiKKota($value)
 * @method static Builder|PaudPetugas whereInstansiKodepos($value)
 * @method static Builder|PaudPetugas whereDataAkun($value)
 * @method static Builder|PaudPetugas whereCreatedAt($value)
 * @method static Builder|PaudPetugas whereUpdatedAt($value)
 * @method static Builder|PaudPetugas whereCreatedBy($value)
 * @method static Builder|PaudPetugas whereUpdatedBy($value)
 * @method static Builder|PaudPetugas whereIsInti($value)
 * @method static Builder|PaudPetugas whereIsRefreshment($value)
 * @method static Builder|PaudPetugas whereIsPengimbasan($value)
 * @method static Builder|PaudPetugas whereKUnsurPengajarPaud($value)
 */
class PaudPetugas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_petugas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_petugas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'akun_id'               => 'string',
        'instansi_id'           => 'int',
        'tahun'                 => 'int',
        'angkatan'              => 'int',
        'k_petugas_paud'        => 'int',
        'lulusan'               => 'string',
        'prodi'                 => 'string',
        'k_kualifikasi'         => 'int',
        'k_kecamatan'           => 'int',
        'k_kelurahan'           => 'int',
        'instansi_nama'         => 'string',
        'instansi_jabatan'      => 'string',
        'instansi_alamat'       => 'string',
        'instansi_k_propinsi'   => 'int',
        'instansi_k_kota'       => 'int',
        'instansi_kodepos'      => 'string',
        'data_akun'             => 'array',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'created_by'            => 'string',
        'updated_by'            => 'string',
        'is_inti'               => 'int',
        'is_refreshment'        => 'int',
        'is_pengimbasan'        => 'int',
        'k_unsur_pengajar_paud' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_petugas_id',
        'akun_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_petugas_paud',
        'lulusan',
        'prodi',
        'k_kualifikasi',
        'k_kecamatan',
        'k_kelurahan',
        'instansi_nama',
        'instansi_jabatan',
        'instansi_alamat',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_kodepos',
        'data_akun',
        'created_by',
        'updated_by',
        'is_inti',
        'is_refreshment',
        'is_pengimbasan',
        'k_unsur_pengajar_paud',
    ];

    /**
     * @return BelongsTo
     */
    public function akun()
    {
        return $this->belongsTo('App\Models\Akun', 'akun_id', 'akun_id');
    }

    /**
     * @return BelongsTo
     */
    public function instansi()
    {
        return $this->belongsTo('App\Models\Instansi', 'instansi_id', 'instansi_id');
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
    public function mPetugasPaud()
    {
        return $this->belongsTo('App\Models\MPetugasPaud', 'k_petugas_paud', 'k_petugas_paud');
    }

    /**
     * @return BelongsTo
     */
    public function mUnsurPengajarPaud()
    {
        return $this->belongsTo('App\Models\MUnsurPengajarPaud', 'k_unsur_pengajar_paud', 'k_unsur_pengajar_paud');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasBerkases()
    {
        return $this->hasMany('App\Models\PaudPetugasBerkas', 'paud_petugas_id', 'paud_petugas_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasDiklats()
    {
        return $this->hasMany('App\Models\PaudPetugasDiklat', 'paud_petugas_id', 'paud_petugas_id');
    }

    /**
     * @return HasMany
     */
    public function paudPetugasPerans()
    {
        return $this->hasMany('App\Models\PaudPetugasPeran', 'paud_petugas_id', 'paud_petugas_id');
    }

    /**
     * @return HasMany
     */
    public function peranPaudPetugasPeranBerkases()
    {
        return $this->hasMany('App\Models\PaudPetugasPeranBerkas', 'paud_petugas_peran_id', 'paud_petugas_id');
    }
}
