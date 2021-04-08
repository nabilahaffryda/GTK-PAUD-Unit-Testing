<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MPropinsi
 *
 * @property int $k_propinsi
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|string $kode_ukg
 * @property null|int $timezone
 *
 * @property-read Collection|Akun[] $akuns
 * @property-read Collection|Akun[] $instansiPropinsiAkuns
 * @property-read Collection|Instansi[] $instansis
 * @property-read Collection|MKota[] $kotas
 * @property-read Collection|Ptk[] $ptks
 *
 * @method static Builder|MPropinsi whereKPropinsi($value)
 * @method static Builder|MPropinsi whereSingkat($value)
 * @method static Builder|MPropinsi whereKeterangan($value)
 * @method static Builder|MPropinsi whereKodeUkg($value)
 * @method static Builder|MPropinsi whereTimezone($value)
 */
class MPropinsi extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_propinsi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_propinsi';

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
        'singkat'    => 'string',
        'keterangan' => 'string',
        'kode_ukg'   => 'string',
        'timezone'   => 'int',
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
        'k_propinsi',
        'singkat',
        'keterangan',
        'kode_ukg',
        'timezone',
    ];

    /**
     * @return HasMany|Builder|Akun
     */
    public function akuns()
    {
        return $this->hasMany('App\Models\Akun', 'k_propinsi', 'k_propinsi');
    }

    /**
     * @return HasMany|Builder|Akun
     */
    public function instansiPropinsiAkuns()
    {
        return $this->hasMany('App\Models\Akun', 'instansi_k_propinsi', 'k_propinsi');
    }

    /**
     * @return HasMany|Builder|Instansi
     */
    public function instansis()
    {
        return $this->hasMany('App\Models\Instansi', 'k_propinsi', 'k_propinsi');
    }

    /**
     * @return HasMany|Builder|MKota
     */
    public function kotas()
    {
        return $this->hasMany('App\Models\MKota', 'k_propinsi', 'k_propinsi');
    }

    /**
     * @return HasMany|Builder|Ptk
     */
    public function ptks()
    {
        return $this->hasMany('App\Models\Ptk', 'k_propinsi', 'k_propinsi');
    }
}
