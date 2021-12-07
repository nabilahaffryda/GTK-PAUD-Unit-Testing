<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudKelasPeserta
 *
 * @property int $paud_kelas_peserta_id
 * @property null|int $paud_kelas_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|string $ptk_id
 * @property null|int $k_konfirmasi_paud
 * @property null|string $alasan
 * @property null|int $is_survey
 * @property null|Carbon $wkt_survey
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
 * @property-read PaudKelas $paudKelas
 * @property-read Ptk $ptk
 *
 * @method static Builder|PaudKelasPeserta wherePaudKelasPesertaId($value)
 * @method static Builder|PaudKelasPeserta wherePaudKelasId($value)
 * @method static Builder|PaudKelasPeserta whereTahun($value)
 * @method static Builder|PaudKelasPeserta whereAngkatan($value)
 * @method static Builder|PaudKelasPeserta wherePtkId($value)
 * @method static Builder|PaudKelasPeserta whereKKonfirmasiPaud($value)
 * @method static Builder|PaudKelasPeserta whereAlasan($value)
 * @method static Builder|PaudKelasPeserta whereIsSurvey($value)
 * @method static Builder|PaudKelasPeserta whereWktSurvey($value)
 * @method static Builder|PaudKelasPeserta whereIsLulus($value)
 * @method static Builder|PaudKelasPeserta whereNilai($value)
 * @method static Builder|PaudKelasPeserta wherePredikat($value)
 * @method static Builder|PaudKelasPeserta whereMedali($value)
 * @method static Builder|PaudKelasPeserta whereNPendalamanMateri($value)
 * @method static Builder|PaudKelasPeserta whereNTugasMandiri($value)
 * @method static Builder|PaudKelasPeserta whereWktDownload($value)
 * @method static Builder|PaudKelasPeserta whereUrlDownload($value)
 * @method static Builder|PaudKelasPeserta whereCreatedAt($value)
 * @method static Builder|PaudKelasPeserta whereUpdatedAt($value)
 * @method static Builder|PaudKelasPeserta whereCreatedBy($value)
 * @method static Builder|PaudKelasPeserta whereUpdatedBy($value)
 */
class PaudKelasPeserta extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas_peserta';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_peserta_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_kelas_id'       => 'int',
        'tahun'               => 'int',
        'angkatan'            => 'int',
        'ptk_id'              => 'string',
        'k_konfirmasi_paud'   => 'int',
        'alasan'              => 'string',
        'is_survey'           => 'int',
        'wkt_survey'          => 'datetime',
        'is_lulus'            => 'int',
        'nilai'               => 'float',
        'predikat'            => 'string',
        'medali'              => 'string',
        'n_pendalaman_materi' => 'float',
        'n_tugas_mandiri'     => 'float',
        'wkt_download'        => 'datetime',
        'url_download'        => 'string',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'created_by'          => 'string',
        'updated_by'          => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_kelas_peserta_id',
        'paud_kelas_id',
        'tahun',
        'angkatan',
        'ptk_id',
        'k_konfirmasi_paud',
        'alasan',
        'is_survey',
        'wkt_survey',
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
    public function paudKelas()
    {
        return $this->belongsTo('App\Models\PaudKelas', 'paud_kelas_id', 'paud_kelas_id');
    }

    /**
     * @return BelongsTo
     */
    public function ptk()
    {
        return $this->belongsTo('App\Models\Ptk', 'ptk_id', 'ptk_id');
    }
}
