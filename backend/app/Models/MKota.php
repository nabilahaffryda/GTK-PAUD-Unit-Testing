<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MKota
 *
 * @property int $k_kota
 * @property int $k_propinsi
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|string $kode_ukg
 * @property null|int $is_kota
 * @property null|int $is_ibukota
 * @property null|int $is_3t
 * @property null|int $timezone
 * @property null|int $is_aktif
 * @property null|string $kode_dapodik
 * @property null|int $kode_dagri
 *
 * @property-read MPropinsi $mPropinsi
 * @property-read Collection|Akun[] $akuns
 * @property-read Collection|Akun[] $instansiAkuns
 * @property-read Collection|PaudPetugas[] $instansiPetugases
 * @property-read Collection|Instansi[] $instansis
 * @property-read Collection|PaudDiklat[] $paudDiklats
 * @property-read Collection|Ptk[] $ptks
 *
 * @method static Builder|MKota whereKKota($value)
 * @method static Builder|MKota whereKPropinsi($value)
 * @method static Builder|MKota whereSingkat($value)
 * @method static Builder|MKota whereKeterangan($value)
 * @method static Builder|MKota whereKodeUkg($value)
 * @method static Builder|MKota whereIsKota($value)
 * @method static Builder|MKota whereIsIbukota($value)
 * @method static Builder|MKota whereIs3t($value)
 * @method static Builder|MKota whereTimezone($value)
 * @method static Builder|MKota whereIsAktif($value)
 * @method static Builder|MKota whereKodeDapodik($value)
 * @method static Builder|MKota whereKodeDagri($value)
 */
class MKota extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_kota';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_kota';

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
        'k_propinsi'   => 'int',
        'singkat'      => 'string',
        'keterangan'   => 'string',
        'kode_ukg'     => 'string',
        'is_kota'      => 'int',
        'is_ibukota'   => 'int',
        'is_3t'        => 'int',
        'timezone'     => 'int',
        'is_aktif'     => 'int',
        'kode_dapodik' => 'string',
        'kode_dagri'   => 'int',
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
        'k_kota',
        'k_propinsi',
        'singkat',
        'keterangan',
        'kode_ukg',
        'is_kota',
        'is_ibukota',
        'is_3t',
        'timezone',
        'is_aktif',
        'kode_dapodik',
        'kode_dagri',
    ];

    /**
     * @return HasMany
     */
    public function akuns()
    {
        return $this->hasMany('App\Models\Akun', 'k_kota', 'k_kota');
    }

    /**
     * @return HasMany
     */
    public function instansiAkuns()
    {
        return $this->hasMany('App\Models\Akun', 'instansi_k_kota', 'k_kota');
    }

    /**
     * @return HasMany
     */
    public function instansiPetugases()
    {
        return $this->hasMany('App\Models\PaudPetugas', 'instansi_k_kota', 'k_kota');
    }

    /**
     * @return HasMany
     */
    public function instansis()
    {
        return $this->hasMany('App\Models\Instansi', 'k_kota', 'k_kota');
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
    public function paudDiklats()
    {
        return $this->hasMany('App\Models\PaudDiklat', 'k_kota', 'k_kota');
    }

    /**
     * @return HasMany
     */
    public function ptks()
    {
        return $this->hasMany('App\Models\Ptk', 'k_kota', 'k_kota');
    }
}
