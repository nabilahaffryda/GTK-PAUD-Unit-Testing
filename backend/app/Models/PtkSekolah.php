<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PtkSekolah
 *
 * @property int $ptk_sekolah_id
 * @property string $ptk_id
 * @property int $sekolah_id
 * @property null|int $ptk_induk
 * @property null|int $tahun
 * @property null|int $semester
 * @property Carbon $created_at
 * @property null|Carbon $updated_at
 * @property int $is_aktif
 *
 * @property-read Ptk $ptk
 * @property-read Sekolah $sekolah
 *
 * @method static Builder|PtkSekolah wherePtkSekolahId($value)
 * @method static Builder|PtkSekolah wherePtkId($value)
 * @method static Builder|PtkSekolah whereSekolahId($value)
 * @method static Builder|PtkSekolah wherePtkInduk($value)
 * @method static Builder|PtkSekolah whereTahun($value)
 * @method static Builder|PtkSekolah whereSemester($value)
 * @method static Builder|PtkSekolah whereCreatedAt($value)
 * @method static Builder|PtkSekolah whereUpdatedAt($value)
 * @method static Builder|PtkSekolah whereIsAktif($value)
 */
class PtkSekolah extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ptk_sekolah';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ptk_sekolah_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'ptk_id'     => 'string',
        'sekolah_id' => 'int',
        'ptk_induk'  => 'int',
        'tahun'      => 'int',
        'semester'   => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_aktif'   => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'ptk_sekolah_id',
        'ptk_id',
        'sekolah_id',
        'ptk_induk',
        'tahun',
        'semester',
        'is_aktif',
    ];

    /**
     * @return BelongsTo
     */
    public function ptk()
    {
        return $this->belongsTo('App\Models\Ptk', 'ptk_id', 'ptk_id');
    }

    /**
     * @return BelongsTo
     */
    public function sekolah()
    {
        return $this->belongsTo('App\Models\Sekolah', 'sekolah_id', 'sekolah_id');
    }
}
