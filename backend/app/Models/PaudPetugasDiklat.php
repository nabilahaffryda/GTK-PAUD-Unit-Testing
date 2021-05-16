<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudPetugasDiklat
 *
 * @property int $paud_petugas_diklat_id
 * @property null|int $paud_petugas_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|int $k_diklat_paud
 * @property null|string $nama
 * @property null|string $penyelenggara
 * @property null|int $k_tingkat_diklat_paud
 * @property null|string $tingkatan
 * @property null|int $tahun_diklat
 * @property null|string $nama_file
 * @property null|string $file
 * @property null|string $keterangan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read Akun $akun
 * @property-read MDiklatPaud $mDiklatPaud
 * @property-read MTingkatDiklatPaud $mTingkatDiklatPaud
 * @property-read PaudPetugas $paudPetugas
 *
 * @method static Builder|PaudPetugasDiklat wherePaudPetugasDiklatId($value)
 * @method static Builder|PaudPetugasDiklat wherePaudPetugasId($value)
 * @method static Builder|PaudPetugasDiklat whereAkunId($value)
 * @method static Builder|PaudPetugasDiklat whereTahun($value)
 * @method static Builder|PaudPetugasDiklat whereAngkatan($value)
 * @method static Builder|PaudPetugasDiklat whereKDiklatPaud($value)
 * @method static Builder|PaudPetugasDiklat whereNama($value)
 * @method static Builder|PaudPetugasDiklat wherePenyelenggara($value)
 * @method static Builder|PaudPetugasDiklat whereKTingkatDiklatPaud($value)
 * @method static Builder|PaudPetugasDiklat whereTingkatan($value)
 * @method static Builder|PaudPetugasDiklat whereTahunDiklat($value)
 * @method static Builder|PaudPetugasDiklat whereNamaFile($value)
 * @method static Builder|PaudPetugasDiklat whereFile($value)
 * @method static Builder|PaudPetugasDiklat whereKeterangan($value)
 * @method static Builder|PaudPetugasDiklat whereCreatedAt($value)
 * @method static Builder|PaudPetugasDiklat whereUpdatedAt($value)
 * @method static Builder|PaudPetugasDiklat whereCreatedBy($value)
 * @method static Builder|PaudPetugasDiklat whereUpdatedBy($value)
 */
class PaudPetugasDiklat extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_petugas_diklat';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_petugas_diklat_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_petugas_id'       => 'int',
        'akun_id'               => 'string',
        'tahun'                 => 'int',
        'angkatan'              => 'int',
        'k_diklat_paud'         => 'int',
        'nama'                  => 'string',
        'penyelenggara'         => 'string',
        'k_tingkat_diklat_paud' => 'int',
        'tingkatan'             => 'string',
        'tahun_diklat'          => 'int',
        'nama_file'             => 'string',
        'file'                  => 'string',
        'keterangan'            => 'string',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
        'created_by'            => 'string',
        'updated_by'            => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_petugas_diklat_id',
        'paud_petugas_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_diklat_paud',
        'nama',
        'penyelenggara',
        'k_tingkat_diklat_paud',
        'tingkatan',
        'tahun_diklat',
        'nama_file',
        'file',
        'keterangan',
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
    public function mDiklatPaud()
    {
        return $this->belongsTo('App\Models\MDiklatPaud', 'k_diklat_paud', 'k_diklat_paud');
    }

    /**
     * @return BelongsTo
     */
    public function mTingkatDiklatPaud()
    {
        return $this->belongsTo('App\Models\MTingkatDiklatPaud', 'k_tingkat_diklat_paud', 'k_tingkat_diklat_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudPetugas()
    {
        return $this->belongsTo('App\Models\PaudPetugas', 'paud_petugas_id', 'paud_petugas_id');
    }
}
