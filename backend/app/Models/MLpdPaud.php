<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MLpdPaud
 *
 * @property int $k_lpd_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudInstansi[] $paudInstansis
 *
 * @method static Builder|MLpdPaud whereKLpdPaud($value)
 * @method static Builder|MLpdPaud whereSingkat($value)
 * @method static Builder|MLpdPaud whereKeterangan($value)
 */
class MLpdPaud extends Eloquent
{
    public const LPD_PUSAT          = 1;
    public const LPD_PROVINSI       = 2;
    public const LPD_KOTA_KABUPATEN = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_lpd_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_lpd_paud';

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
        'k_lpd_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudInstansis()
    {
        return $this->hasMany('App\Models\PaudInstansi', 'k_lpd_paud', 'k_lpd_paud');
    }
}
