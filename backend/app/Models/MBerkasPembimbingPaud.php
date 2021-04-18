<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MBerkasPembimbingPaud
 *
 * @property int $k_berkas_pembimbing_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|int $maks
 * @property null|string $validasi
 *
 * @property-read Collection|PaudPembimbingBerkas[] $paudPembimbingBerkases
 *
 * @method static Builder|MBerkasPembimbingPaud whereKBerkasPembimbingPaud($value)
 * @method static Builder|MBerkasPembimbingPaud whereSingkat($value)
 * @method static Builder|MBerkasPembimbingPaud whereKeterangan($value)
 * @method static Builder|MBerkasPembimbingPaud whereMaks($value)
 * @method static Builder|MBerkasPembimbingPaud whereValidasi($value)
 */
class MBerkasPembimbingPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_berkas_pembimbing_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_berkas_pembimbing_paud';

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
        'maks'       => 'int',
        'validasi'   => 'string',
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
        'k_berkas_pembimbing_paud',
        'singkat',
        'keterangan',
        'maks',
        'validasi',
    ];

    /**
     * @return HasMany
     */
    public function paudPembimbingBerkases()
    {
        return $this->hasMany('App\Models\PaudPembimbingBerkas', 'k_berkas_pembimbing_paud', 'k_berkas_pembimbing_paud');
    }
}
