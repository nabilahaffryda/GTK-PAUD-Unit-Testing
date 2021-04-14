<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MBerkasPaud
 *
 * @property int $k_berkas_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|string $validasi
 * @property null|int $urut_lpd
 * @property null|int $urut_pengajar
 * @property null|int $urut_pembimbing_praktik
 *
 * @property-read Collection|PaudInstansiBerkas[] $paudInstansiBerkases
 * @property-read Collection|PaudPengajarBerkas[] $paudPengajarBerkases
 *
 * @method static Builder|MBerkasPaud whereKBerkasPaud($value)
 * @method static Builder|MBerkasPaud whereSingkat($value)
 * @method static Builder|MBerkasPaud whereKeterangan($value)
 * @method static Builder|MBerkasPaud whereValidasi($value)
 * @method static Builder|MBerkasPaud whereUrutLpd($value)
 * @method static Builder|MBerkasPaud whereUrutPengajar($value)
 * @method static Builder|MBerkasPaud whereUrutPembimbingPraktik($value)
 */
class MBerkasPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_berkas_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_berkas_paud';

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
        'singkat'                 => 'string',
        'keterangan'              => 'string',
        'validasi'                => 'string',
        'urut_lpd'                => 'int',
        'urut_pengajar'           => 'int',
        'urut_pembimbing_praktik' => 'int',
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
        'k_berkas_paud',
        'singkat',
        'keterangan',
        'validasi',
        'urut_lpd',
        'urut_pengajar',
        'urut_pembimbing_praktik',
    ];

    /**
     * @return HasMany|Builder|PaudInstansiBerkas
     */
    public function paudInstansiBerkases()
    {
        return $this->hasMany('App\Models\PaudInstansiBerkas', 'k_berkas_paud', 'k_berkas_paud');
    }

    /**
     * @return HasMany|Builder|PaudPengajarBerkas
     */
    public function paudPengajarBerkases()
    {
        return $this->hasMany('App\Models\PaudPengajarBerkas', 'k_berkas_paud', 'k_berkas_paud');
    }
}
