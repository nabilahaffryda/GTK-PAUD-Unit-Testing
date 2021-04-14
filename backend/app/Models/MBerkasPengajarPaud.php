<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MBerkasPengajarPaud
 *
 * @property int $k_berkas_pengajar_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|int $maks
 * @property null|string $validasi
 *
 * @property-read Collection|PaudPengajarBerkas[] $paudPengajarBerkases
 *
 * @method static Builder|MBerkasPengajarPaud whereKBerkasPengajarPaud($value)
 * @method static Builder|MBerkasPengajarPaud whereSingkat($value)
 * @method static Builder|MBerkasPengajarPaud whereKeterangan($value)
 * @method static Builder|MBerkasPengajarPaud whereMaks($value)
 * @method static Builder|MBerkasPengajarPaud whereValidasi($value)
 */
class MBerkasPengajarPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_berkas_pengajar_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_berkas_pengajar_paud';

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
        'k_berkas_pengajar_paud',
        'singkat',
        'keterangan',
        'maks',
        'validasi',
    ];

    /**
     * @return HasMany|Builder|PaudPengajarBerkas
     */
    public function paudPengajarBerkases()
    {
        return $this->hasMany('App\Models\PaudPengajarBerkas', 'k_berkas_pengajar_paud', 'k_berkas_pengajar_paud');
    }
}
