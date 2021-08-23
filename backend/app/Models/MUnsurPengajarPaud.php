<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MUnsurPengajarPaud
 *
 * @property int $k_unsur_pengajar_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudPetugas[] $paudPetugases
 *
 * @method static Builder|MUnsurPengajarPaud whereKUnsurPengajarPaud($value)
 * @method static Builder|MUnsurPengajarPaud whereSingkat($value)
 * @method static Builder|MUnsurPengajarPaud whereKeterangan($value)
 */
class MUnsurPengajarPaud extends Eloquent
{
    public const UNSUR_GURU  = 1;
    public const UNSUR_DOSEN = 2;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_unsur_pengajar_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_unsur_pengajar_paud';

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
        'k_unsur_pengajar_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudPetugases()
    {
        return $this->hasMany('App\Models\PaudPetugas', 'k_unsur_pengajar_paud', 'k_unsur_pengajar_paud');
    }
}
