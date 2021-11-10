<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/**
 * App\Models\PaudKelasPetugasLuring
 *
 * @property int $paud_kelas_petugas_luring_id
 * @property null|int $paud_kelas_luring_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|int $k_petugas_paud
 * @property null|string $akun_id
 * @property null|int $paud_petugas_id
 * @property null|int $k_konfirmasi_paud
 * @property null|string $alasan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read MKonfirmasiPaud $mKonfirmasiPaud
 * @property-read MPetugasPaud $mPetugasPaud
 * @property-read PaudKelasLuring $paudKelasLuring
 * @property-read PaudPetugas $paudPetugas
 *
 * @method static Builder|PaudKelasPetugasLuring wherePaudKelasPetugasLuringId($value)
 * @method static Builder|PaudKelasPetugasLuring wherePaudKelasLuringId($value)
 * @method static Builder|PaudKelasPetugasLuring whereTahun($value)
 * @method static Builder|PaudKelasPetugasLuring whereAngkatan($value)
 * @method static Builder|PaudKelasPetugasLuring whereKPetugasPaud($value)
 * @method static Builder|PaudKelasPetugasLuring whereAkunId($value)
 * @method static Builder|PaudKelasPetugasLuring wherePaudPetugasId($value)
 * @method static Builder|PaudKelasPetugasLuring whereKKonfirmasiPaud($value)
 * @method static Builder|PaudKelasPetugasLuring whereAlasan($value)
 * @method static Builder|PaudKelasPetugasLuring whereCreatedAt($value)
 * @method static Builder|PaudKelasPetugasLuring whereUpdatedAt($value)
 * @method static Builder|PaudKelasPetugasLuring whereCreatedBy($value)
 * @method static Builder|PaudKelasPetugasLuring whereUpdatedBy($value)
 */
class PaudKelasPetugasLuring extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas_petugas_luring';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_petugas_luring_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_kelas_luring_id' => 'int',
        'tahun' => 'int',
        'angkatan' => 'int',
        'k_petugas_paud' => 'int',
        'akun_id' => 'string',
        'paud_petugas_id' => 'int',
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
        'paud_kelas_petugas_luring_id',
        'paud_kelas_luring_id',
        'tahun',
        'angkatan',
        'k_petugas_paud',
        'akun_id',
        'paud_petugas_id',
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
    public function mPetugasPaud()
    {
        return $this->belongsTo('App\Models\MPetugasPaud', 'k_petugas_paud', 'k_petugas_paud');
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
    public function paudPetugas()
    {
        return $this->belongsTo('App\Models\PaudPetugas', 'paud_petugas_id', 'paud_petugas_id');
    }
}
