<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudPeriode
 *
 * @property int $paud_periode_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|string $nama
 * @property null|Carbon $tgl_daftar_mulai
 * @property null|Carbon $tgl_daftar_selesai
 * @property null|Carbon $tgl_diklat_mulai
 * @property null|Carbon $tgl_diklat_selesai
 * @property null|Carbon $tgl_tugas_mulai
 * @property null|Carbon $tgl_tugas_selesai
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|int $is_aktif
 * @property null|Carbon $wkt_ajuan_buka
 * @property null|Carbon $wkt_ajuan_tutup
 *
 * @property-read Collection|PaudDiklat[] $paudDiklats
 *
 * @method static Builder|PaudPeriode wherePaudPeriodeId($value)
 * @method static Builder|PaudPeriode whereTahun($value)
 * @method static Builder|PaudPeriode whereAngkatan($value)
 * @method static Builder|PaudPeriode whereNama($value)
 * @method static Builder|PaudPeriode whereTglDaftarMulai($value)
 * @method static Builder|PaudPeriode whereTglDaftarSelesai($value)
 * @method static Builder|PaudPeriode whereTglDiklatMulai($value)
 * @method static Builder|PaudPeriode whereTglDiklatSelesai($value)
 * @method static Builder|PaudPeriode whereTglTugasMulai($value)
 * @method static Builder|PaudPeriode whereTglTugasSelesai($value)
 * @method static Builder|PaudPeriode whereCreatedAt($value)
 * @method static Builder|PaudPeriode whereUpdatedAt($value)
 * @method static Builder|PaudPeriode whereCreatedBy($value)
 * @method static Builder|PaudPeriode whereUpdatedBy($value)
 * @method static Builder|PaudPeriode whereIsAktif($value)
 * @method static Builder|PaudPeriode whereWktAjuanBuka($value)
 * @method static Builder|PaudPeriode whereWktAjuanTutup($value)
 */
class PaudPeriode extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_periode';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_periode_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tahun'              => 'int',
        'angkatan'           => 'int',
        'nama'               => 'string',
        'tgl_daftar_mulai'   => 'date:Y-m-d',
        'tgl_daftar_selesai' => 'date:Y-m-d',
        'tgl_diklat_mulai'   => 'date:Y-m-d',
        'tgl_diklat_selesai' => 'date:Y-m-d',
        'tgl_tugas_mulai'    => 'date:Y-m-d',
        'tgl_tugas_selesai'  => 'date:Y-m-d',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
        'created_by'         => 'string',
        'updated_by'         => 'string',
        'is_aktif'           => 'int',
        'wkt_ajuan_buka'     => 'datetime',
        'wkt_ajuan_tutup'    => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_periode_id',
        'tahun',
        'angkatan',
        'nama',
        'tgl_daftar_mulai',
        'tgl_daftar_selesai',
        'tgl_diklat_mulai',
        'tgl_diklat_selesai',
        'tgl_tugas_mulai',
        'tgl_tugas_selesai',
        'created_by',
        'updated_by',
        'is_aktif',
        'wkt_ajuan_buka',
        'wkt_ajuan_tutup',
    ];

    /**
     * @return HasMany
     */
    public function paudDiklats()
    {
        return $this->hasMany('App\Models\PaudDiklat', 'paud_periode_id', 'paud_periode_id');
    }
}
