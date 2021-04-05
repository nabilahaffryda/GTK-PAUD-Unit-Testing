<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MVervalPaud
 *
 * @property int $k_verval_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudInstansi[] $paudInstansis
 *
 * @method static Builder|MVervalPaud whereKVervalPaud($value)
 * @method static Builder|MVervalPaud whereSingkat($value)
 * @method static Builder|MVervalPaud whereKeterangan($value)
 */
class MVervalPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_verval_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_verval_paud';

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
        'k_verval_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany|Builder|PaudInstansi
     */
    public function paudInstansis()
    {
        return $this->hasMany('App\Models\PaudInstansi', 'k_verval_paud', 'k_verval_paud');
    }
}