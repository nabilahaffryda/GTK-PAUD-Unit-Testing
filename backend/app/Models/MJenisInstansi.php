<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MJenisInstansi
 *
 * @property int $k_jenis_instansi
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|Instansi[] $instansis
 * @property-read Collection|MGroup[] $mGroups
 *
 * @method static Builder|MJenisInstansi whereKJenisInstansi($value)
 * @method static Builder|MJenisInstansi whereSingkat($value)
 * @method static Builder|MJenisInstansi whereKeterangan($value)
 */
class MJenisInstansi extends Eloquent
{
    public const SEKOLAH = 1;
    public const DINAS_KOTA = 2;
    public const DINAS_PROPINSI = 3;
    public const DEPAG = 4;
    public const KANWIL_DEPAG = 5;
    public const LPMP = 6;
    public const KEMDIKBUD = 7;
    public const P4TK = 8;
    public const KEMENAG = 9;
    public const DIKDAS = 10;
    public const DIKMEN = 11;
    public const TENDIK = 12;
    public const LP2KS = 13;
    public const PAUD = 14;
    public const LPTK = 15;
    public const EVALUATOR_PENGGERAK = 16;
    public const GPR = 17;
    public const VOKASI = 18;
    public const LPD = 19;
    public const P3GTK = 20;
    public const BPPAUD = 21;
    public const PL_POP = 22;
    public const LPD_PAUD = 23;
    public const MITRAS_DUDI = 24;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_jenis_instansi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_jenis_instansi';

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
        'k_jenis_instansi',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function instansis()
    {
        return $this->hasMany('App\Models\Instansi', 'k_jenis_instansi', 'k_jenis_instansi');
    }

    /**
     * @return HasMany
     */
    public function mGroups()
    {
        return $this->hasMany('App\Models\MGroup', 'k_jenis_instansi', 'k_jenis_instansi');
    }
}
