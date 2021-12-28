<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MTahapNilaiLuringPaud
 *
 * @property int $k_tahap_nilai_luring_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|MInstrumenNilaiLuringPaud[] $mInstrumenNilaiLuringPauds
 *
 * @method static Builder|MTahapNilaiLuringPaud whereKTahapNilaiLuringPaud($value)
 * @method static Builder|MTahapNilaiLuringPaud whereSingkat($value)
 * @method static Builder|MTahapNilaiLuringPaud whereKeterangan($value)
 */
class MTahapNilaiLuringPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_tahap_nilai_luring_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_tahap_nilai_luring_paud';

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
        'k_tahap_nilai_luring_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function mInstrumenNilaiLuringPauds()
    {
        return $this->hasMany('App\Models\MInstrumenNilaiLuringPaud', 'k_tahap_nilai_luring_paud', 'k_tahap_nilai_luring_paud');
    }
}
