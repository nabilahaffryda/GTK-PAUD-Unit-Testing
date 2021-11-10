<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * App\Models\PaudKelasPesertaLuring
 *
 * @property int $paud_kelas_peserta_luring_id
 * @property null|int $paud_kelas_luring_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|int $is_nonptk
 * @property null|string $ptk_id
 * @property null|int $paud_peserta_nonptk_id
 * @property null|int $k_konfirmasi_paud
 * @property null|string $alasan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read MKonfirmasiPaud $mKonfirmasiPaud
 * @property-read PaudKelasLuring $paudKelasLuring
 * @property-read PaudPesertaNonptk $paudPesertaNonptk
 * @property-read Ptk $ptk
 *
 * @method static Builder|PaudKelasPesertaLuring wherePaudKelasPesertaLuringId($value)
 * @method static Builder|PaudKelasPesertaLuring wherePaudKelasLuringId($value)
 * @method static Builder|PaudKelasPesertaLuring whereTahun($value)
 * @method static Builder|PaudKelasPesertaLuring whereAngkatan($value)
 * @method static Builder|PaudKelasPesertaLuring whereIsNonptk($value)
 * @method static Builder|PaudKelasPesertaLuring wherePtkId($value)
 * @method static Builder|PaudKelasPesertaLuring wherePaudPesertaNonptkId($value)
 * @method static Builder|PaudKelasPesertaLuring whereKKonfirmasiPaud($value)
 * @method static Builder|PaudKelasPesertaLuring whereAlasan($value)
 * @method static Builder|PaudKelasPesertaLuring whereCreatedAt($value)
 * @method static Builder|PaudKelasPesertaLuring whereUpdatedAt($value)
 * @method static Builder|PaudKelasPesertaLuring whereCreatedBy($value)
 * @method static Builder|PaudKelasPesertaLuring whereUpdatedBy($value)
 */
class PaudKelasPesertaLuring extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas_peserta_luring';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_peserta_luring_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_kelas_luring_id' => 'int',
        'tahun' => 'int',
        'angkatan' => 'int',
        'is_nonptk' => 'int',
        'ptk_id' => 'string',
        'paud_peserta_nonptk_id' => 'int',
        'k_konfirmasi_paud' => 'int',
        'alasan' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'string',
        'updated_by' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_kelas_peserta_luring_id',
        'paud_kelas_luring_id',
        'tahun',
        'angkatan',
        'is_nonptk',
        'ptk_id',
        'paud_peserta_nonptk_id',
        'k_konfirmasi_paud',
        'alasan',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function mKonfirmasiPaud()
    {
        return $this->belongsTo('App\Models\MKonfirmasiPaud', 'k_konfirmasi_paud', 'k_konfirmasi_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudKelasLuring()
    {
        return $this->belongsTo('App\Models\PaudKelasLuring', 'paud_kelas_luring_id', 'paud_kelas_luring_id');
    }

    /**
     * @return BelongsTo
     */
    public function paudPesertaNonptk()
    {
        return $this->belongsTo('App\Models\PaudPesertaNonptk', 'paud_peserta_nonptk_id', 'paud_peserta_nonptk_id');
    }

    /**
     * @return BelongsTo
     */
    public function ptk()
    {
        return $this->belongsTo('App\Models\Ptk', 'ptk_id', 'ptk_id');
    }
}
