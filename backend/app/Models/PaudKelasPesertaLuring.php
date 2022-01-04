<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property null|int $is_lulus
 * @property null|float $nilai
 * @property null|string $predikat
 * @property null|string $medali
 * @property null|float $n_pendalaman_materi
 * @property null|float $n_tugas_mandiri
 * @property null|Carbon $wkt_download
 * @property null|string $url_download
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read MKonfirmasiPaud $mKonfirmasiPaud
 * @property-read PaudKelasLuring $paudKelasLuring
 * @property-read PaudPesertaNonptk $paudPesertaNonptk
 * @property-read Ptk $ptk
 * @property-read Collection|PaudKelasPesertaLuringNilai[] $paudKelasPesertaLuringNilais
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
 * @method static Builder|PaudKelasPesertaLuring whereIsLulus($value)
 * @method static Builder|PaudKelasPesertaLuring whereNilai($value)
 * @method static Builder|PaudKelasPesertaLuring wherePredikat($value)
 * @method static Builder|PaudKelasPesertaLuring whereMedali($value)
 * @method static Builder|PaudKelasPesertaLuring whereNPendalamanMateri($value)
 * @method static Builder|PaudKelasPesertaLuring whereNTugasMandiri($value)
 * @method static Builder|PaudKelasPesertaLuring whereWktDownload($value)
 * @method static Builder|PaudKelasPesertaLuring whereUrlDownload($value)
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
        'is_lulus' => 'int',
        'nilai' => 'float',
        'predikat' => 'string',
        'medali' => 'string',
        'n_pendalaman_materi' => 'float',
        'n_tugas_mandiri' => 'float',
        'wkt_download' => 'datetime',
        'url_download' => 'string',
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
        'is_lulus',
        'nilai',
        'predikat',
        'medali',
        'n_pendalaman_materi',
        'n_tugas_mandiri',
        'wkt_download',
        'url_download',
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
     * @return HasMany
     */
    public function paudKelasPesertaLuringNilais()
    {
        return $this->hasMany('App\Models\PaudKelasPesertaLuringNilai', 'paud_kelas_peserta_luring_id', 'paud_kelas_peserta_luring_id');
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
