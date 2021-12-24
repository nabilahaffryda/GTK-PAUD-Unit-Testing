<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MInstrumenNilaiLuringPaud
 *
 * @property int $k_instrumen_nilai_luring_paud
 * @property null|int $k_tahap_nilai_luring_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|int $urutan
 *
 * @property-read MTahapNilaiLuringPaud $mTahapNilaiLuringPaud
 * @property-read Collection|PaudKelasPesertaLuringNilai[] $paudKelasPesertaLuringNilais
 *
 * @method static Builder|MInstrumenNilaiLuringPaud whereKInstrumenNilaiLuringPaud($value)
 * @method static Builder|MInstrumenNilaiLuringPaud whereKTahapNilaiLuringPaud($value)
 * @method static Builder|MInstrumenNilaiLuringPaud whereSingkat($value)
 * @method static Builder|MInstrumenNilaiLuringPaud whereKeterangan($value)
 * @method static Builder|MInstrumenNilaiLuringPaud whereUrutan($value)
 */
class MInstrumenNilaiLuringPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_instrumen_nilai_luring_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_instrumen_nilai_luring_paud';

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
        'k_tahap_nilai_luring_paud' => 'int',
        'singkat'                   => 'string',
        'keterangan'                => 'string',
        'urutan'                    => 'int',
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
        'k_instrumen_nilai_luring_paud',
        'k_tahap_nilai_luring_paud',
        'singkat',
        'keterangan',
        'urutan',
    ];

    /**
     * @return BelongsTo
     */
    public function mTahapNilaiLuringPaud()
    {
        return $this->belongsTo('App\Models\MTahapNilaiLuringPaud', 'k_tahap_nilai_luring_paud', 'k_tahap_nilai_luring_paud');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPesertaLuringNilais()
    {
        return $this->hasMany('App\Models\PaudKelasPesertaLuringNilai', 'k_instrumen_nilai_luring_paud', 'k_instrumen_nilai_luring_paud');
    }
}
