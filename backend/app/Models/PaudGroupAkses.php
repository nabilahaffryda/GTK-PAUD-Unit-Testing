<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudGroupAkses
 *
 * @property int $paud_group_akses_id
 * @property int $paud_akses_id
 * @property null|int $k_group
 * @property int $is_aktif
 *
 * @property-read MGroup $mGroup
 * @property-read PaudAkses $paudAkses
 *
 * @method static Builder|PaudGroupAkses wherePaudGroupAksesId($value)
 * @method static Builder|PaudGroupAkses wherePaudAksesId($value)
 * @method static Builder|PaudGroupAkses whereKGroup($value)
 * @method static Builder|PaudGroupAkses whereIsAktif($value)
 */
class PaudGroupAkses extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_group_akses2';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_group_akses_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_akses_id' => 'int',
        'k_group'       => 'int',
        'is_aktif'      => 'int',
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
        'paud_group_akses_id',
        'paud_akses_id',
        'k_group',
        'is_aktif',
    ];

    /**
     * @return BelongsTo
     */
    public function mGroup()
    {
        return $this->belongsTo('App\Models\MGroup', 'k_group', 'k_group');
    }

    /**
     * @return BelongsTo
     */
    public function paudAkses()
    {
        return $this->belongsTo('App\Models\PaudAkses', 'paud_akses_id', 'paud_akses_id');
    }
}
