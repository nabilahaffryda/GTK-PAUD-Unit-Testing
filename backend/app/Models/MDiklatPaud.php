<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MDiklatPaud
 *
 * @property int $k_diklat_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudPetugasDiklat[] $paudPetugasDiklats
 *
 * @method static Builder|MDiklatPaud whereKDiklatPaud($value)
 * @method static Builder|MDiklatPaud whereSingkat($value)
 * @method static Builder|MDiklatPaud whereKeterangan($value)
 */
class MDiklatPaud extends Eloquent
{
    public const DIKLAT_BERJENJANG = 1;
    public const DIKLAT_PCP        = 2;
    public const DIKLAT_MOT        = 3;
    public const DIKLAT_LAINNYA    = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_diklat_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_diklat_paud';

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
        'k_diklat_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudPetugasDiklats()
    {
        return $this->hasMany('App\Models\PaudPetugasDiklat', 'k_diklat_paud', 'k_diklat_paud');
    }
}
