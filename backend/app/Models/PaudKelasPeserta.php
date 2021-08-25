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
        'paud_kelas_id' => 'int',
        'tahun'         => 'int',
        'angkatan'      => 'int',
        'ptk_id'        => 'string',
        'k_konfirmasi_paud' => 'int',
        'alasan' => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'created_by'    => 'string',
        'updated_by'    => 'string',
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
