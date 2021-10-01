<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudMapelKelas
 *
 * @property int $paud_mapel_kelas_id
 * @property null|string $nama
 * @property null|string $is_aktif
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|int $lms_mapel_id
 *
 * @property-read Collection|PaudKelas[] $paudKelases
 *
 * @method static Builder|PaudMapelKelas wherePaudMapelKelasId($value)
 * @method static Builder|PaudMapelKelas whereNama($value)
 * @method static Builder|PaudMapelKelas whereIsAktif($value)
 * @method static Builder|PaudMapelKelas whereCreatedAt($value)
 * @method static Builder|PaudMapelKelas whereUpdatedAt($value)
 * @method static Builder|PaudMapelKelas whereCreatedBy($value)
 * @method static Builder|PaudMapelKelas whereUpdatedBy($value)
 * @method static Builder|PaudMapelKelas whereLmsMapelId($value)
 */
class PaudMapelKelas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_mapel_kelas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_mapel_kelas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nama'         => 'string',
        'is_aktif'     => 'string',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'created_by'   => 'string',
        'updated_by'   => 'string',
        'lms_mapel_id' => 'int',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_mapel_kelas_id',
        'nama',
        'is_aktif',
        'created_by',
        'updated_by',
        'lms_mapel_id',
    ];

    /**
     * @return HasMany
     */
    public function paudKelases()
    {
        return $this->hasMany('App\Models\PaudKelas', 'paud_mapel_kelas_id', 'paud_mapel_kelas_id');
    }
}
