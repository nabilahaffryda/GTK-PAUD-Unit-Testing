<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MPetugasPaud
 *
 * @property int $k_petugas_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudKelasPetugasLuring[] $paudKelasPetugasLurings
 * @property-read Collection|PaudKelasPetugas[] $paudKelasPetugases
 * @property-read Collection|PaudPetugas[] $paudPetugases
 *
 * @method static Builder|MPetugasPaud whereKPetugasPaud($value)
 * @method static Builder|MPetugasPaud whereSingkat($value)
 * @method static Builder|MPetugasPaud whereKeterangan($value)
 */
class MPetugasPaud extends Eloquent
{
    public const PENGAJAR           = 1;
    public const PENGAJAR_TAMBAHAN  = 2;
    public const PEMBIMBING_PRAKTIK = 3;
    public const ADMIN_KELAS        = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_petugas_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_petugas_paud';

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
        'k_petugas_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudKelasPetugasLurings()
    {
        return $this->hasMany('App\Models\PaudKelasPetugasLuring', 'k_petugas_paud', 'k_petugas_paud');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPetugases()
    {
        return $this->hasMany('App\Models\PaudKelasPetugas', 'k_petugas_paud', 'k_petugas_paud');
    }

    /**
     * @return HasMany
     */
    public function paudPetugases()
    {
        return $this->hasMany('App\Models\PaudPetugas', 'k_petugas_paud', 'k_petugas_paud');
    }
}
