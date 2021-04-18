<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudAkses
 *
 * @property int $paud_akses_id
 * @property string $akses
 * @property string $label
 * @property string $guard
 * @property int $is_aktif
 *
 * @property-read Collection|PaudGroupAkses[] $paudGroupAkseses
 *
 * @method static Builder|PaudAkses wherePaudAksesId($value)
 * @method static Builder|PaudAkses whereAkses($value)
 * @method static Builder|PaudAkses whereLabel($value)
 * @method static Builder|PaudAkses whereGuard($value)
 * @method static Builder|PaudAkses whereIsAktif($value)
 */
class PaudAkses extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_akses';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_akses_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'akses'    => 'string',
        'label'    => 'string',
        'guard'    => 'string',
        'is_aktif' => 'int',
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
        'paud_akses_id',
        'akses',
        'label',
        'guard',
        'is_aktif',
    ];

    /**
     * @return HasMany
     */
    public function paudGroupAkseses()
    {
        return $this->hasMany('App\Models\PaudGroupAkses', 'paud_akses_id', 'paud_akses_id');
    }
}
