<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudKelasPesertaLuringNilai
 *
 * @property int $paud_kelas_peserta_luring_nilai_id
 * @property null|int $paud_kelas_peserta_luring_id
 * @property null|int $k_instrumen_nilai_luring_paud
 * @property null|float $nilai
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read MInstrumenNilaiLuringPaud $mInstrumenNilaiLuringPaud
 * @property-read PaudKelasPesertaLuring $paudKelasPesertaLuring
 *
 * @method static Builder|PaudKelasPesertaLuringNilai wherePaudKelasPesertaLuringNilaiId($value)
 * @method static Builder|PaudKelasPesertaLuringNilai wherePaudKelasPesertaLuringId($value)
 * @method static Builder|PaudKelasPesertaLuringNilai whereKInstrumenNilaiLuringPaud($value)
 * @method static Builder|PaudKelasPesertaLuringNilai whereNilai($value)
 * @method static Builder|PaudKelasPesertaLuringNilai whereCreatedAt($value)
 * @method static Builder|PaudKelasPesertaLuringNilai whereUpdatedAt($value)
 * @method static Builder|PaudKelasPesertaLuringNilai whereCreatedBy($value)
 * @method static Builder|PaudKelasPesertaLuringNilai whereUpdatedBy($value)
 */
class PaudKelasPesertaLuringNilai extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas_peserta_luring_nilai';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_peserta_luring_nilai_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_kelas_peserta_luring_id'  => 'int',
        'k_instrumen_nilai_luring_paud' => 'int',
        'nilai'                         => 'float',
        'created_at'                    => 'datetime',
        'updated_at'                    => 'datetime',
        'created_by'                    => 'string',
        'updated_by'                    => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_kelas_peserta_luring_nilai_id',
        'paud_kelas_peserta_luring_id',
        'k_instrumen_nilai_luring_paud',
        'nilai',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function mInstrumenNilaiLuringPaud()
    {
        return $this->belongsTo('App\Models\MInstrumenNilaiLuringPaud', 'k_instrumen_nilai_luring_paud', 'k_instrumen_nilai_luring_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudKelasPesertaLuring()
    {
        return $this->belongsTo('App\Models\PaudKelasPesertaLuring', 'paud_kelas_peserta_luring_id', 'paud_kelas_peserta_luring_id');
    }
}
