<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MBerkasLpdPaud
 *
 * @property int $k_berkas_lpd_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|int $maks
 * @property null|string $validasi
 *
 * @property-read Collection|PaudInstansiBerkas[] $paudInstansiBerkases
 *
 * @method static Builder|MBerkasLpdPaud whereKBerkasLpdPaud($value)
 * @method static Builder|MBerkasLpdPaud whereSingkat($value)
 * @method static Builder|MBerkasLpdPaud whereKeterangan($value)
 * @method static Builder|MBerkasLpdPaud whereMaks($value)
 * @method static Builder|MBerkasLpdPaud whereValidasi($value)
 */
class MBerkasLpdPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_berkas_lpd_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_berkas_lpd_paud';

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
        'k_berkas_lpd_paud',
        'singkat',
        'keterangan',
        'maks',
        'validasi',
    ];

    /**
     * @return HasMany
     */
    public function paudInstansiBerkases()
    {
        return $this->hasMany('App\Models\PaudInstansiBerkas', 'k_berkas_lpd_paud', 'k_berkas_lpd_paud');
    }
}
