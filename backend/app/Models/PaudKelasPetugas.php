<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudKelasPetugas
 *
 * @property int $paud_kelas_petugas_id
 * @property null|int $paud_kelas_id
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
 * @property-read Akun $akun
 * @property-read MKonfirmasiPaud $mKonfirmasiPaud
 * @property-read MPetugasPaud $mPetugasPaud
 * @property-read PaudKelas $paudKelas
 *
 * @method static Builder|PaudKelasPetugas wherePaudKelasPetugasId($value)
 * @method static Builder|PaudKelasPetugas wherePaudKelasId($value)
 * @method static Builder|PaudKelasPetugas whereTahun($value)
 * @method static Builder|PaudKelasPetugas whereAngkatan($value)
 * @method static Builder|PaudKelasPetugas whereKPetugasPaud($value)
 * @method static Builder|PaudKelasPetugas whereAkunId($value)
 * @method static Builder|PaudKelasPetugas wherePaudPetugasId($value)
 * @method static Builder|PaudKelasPetugas whereKKonfirmasiPaud($value)
 * @method static Builder|PaudKelasPetugas whereAlasan($value)
 * @method static Builder|PaudKelasPetugas whereCreatedAt($value)
 * @method static Builder|PaudKelasPetugas whereUpdatedAt($value)
 * @method static Builder|PaudKelasPetugas whereCreatedBy($value)
 * @method static Builder|PaudKelasPetugas whereUpdatedBy($value)
 */
class PaudKelasPetugas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas_petugas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_petugas_id';

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
        'paud_kelas_id'     => 'int',
        'tahun'             => 'int',
        'angkatan'          => 'int',
        'k_petugas_paud'    => 'int',
        'akun_id'           => 'string',
        'paud_petugas_id'   => 'int',
        'k_konfirmasi_paud' => 'int',
        'alasan'            => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'created_by'        => 'string',
        'updated_by'        => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_kelas_petugas_id',
        'paud_kelas_id',
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
    public function akun()
    {
        return $this->belongsTo('App\Models\Akun', 'akun_id', 'akun_id');
    }

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
    public function paudKelas()
    {
        return $this->belongsTo('App\Models\PaudKelas', 'paud_kelas_id', 'paud_kelas_id');
    }
}
