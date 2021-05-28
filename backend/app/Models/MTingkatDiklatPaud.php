<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MTingkatDiklatPaud
 *
 * @property int $k_tingkat_diklat_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudPetugasDiklat[] $paudPetugasDiklats
 *
 * @method static Builder|MTingkatDiklatPaud whereKTingkatDiklatPaud($value)
 * @method static Builder|MTingkatDiklatPaud whereSingkat($value)
 * @method static Builder|MTingkatDiklatPaud whereKeterangan($value)
 */
class MTingkatDiklatPaud extends Eloquent
{
    public const DASAR  = 1;
    public const LANJUT = 2;
    public const MAHIR  = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_tingkat_diklat_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_tingkat_diklat_paud';

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
        'k_tingkat_diklat_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudPetugasDiklats()
    {
        return $this->hasMany('App\Models\PaudPetugasDiklat', 'k_tingkat_diklat_paud', 'k_tingkat_diklat_paud');
    }
}
