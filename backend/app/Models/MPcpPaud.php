<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MPcpPaud
 *
 * @property int $k_pcp_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudPengajar[] $paudPengajars
 *
 * @method static Builder|MPcpPaud whereKPcpPaud($value)
 * @method static Builder|MPcpPaud whereSingkat($value)
 * @method static Builder|MPcpPaud whereKeterangan($value)
 */
class MPcpPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_pcp_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_pcp_paud';

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
        'k_pcp_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudPengajars()
    {
        return $this->hasMany('App\Models\PaudPengajar', 'k_pcp_paud', 'k_pcp_paud');
    }
}
