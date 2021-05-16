<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudPetugasBerkas
 *
 * @property int $paud_petugas_berkas_id
 * @property int $paud_petugas_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_berkas_petugas_paud
 * @property null|string $nama
 * @property null|string $file
 * @property null|string $keterangan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read Akun $akun
 * @property-read MBerkasPetugasPaud $mBerkasPetugasPaud
 * @property-read PaudPetugas $paudPetugas
 *
 * @method static Builder|PaudPetugasBerkas wherePaudPetugasBerkasId($value)
 * @method static Builder|PaudPetugasBerkas wherePaudPetugasId($value)
 * @method static Builder|PaudPetugasBerkas whereAkunId($value)
 * @method static Builder|PaudPetugasBerkas whereTahun($value)
 * @method static Builder|PaudPetugasBerkas whereAngkatan($value)
 * @method static Builder|PaudPetugasBerkas whereKBerkasPetugasPaud($value)
 * @method static Builder|PaudPetugasBerkas whereNama($value)
 * @method static Builder|PaudPetugasBerkas whereFile($value)
 * @method static Builder|PaudPetugasBerkas whereKeterangan($value)
 * @method static Builder|PaudPetugasBerkas whereCreatedAt($value)
 * @method static Builder|PaudPetugasBerkas whereUpdatedAt($value)
 * @method static Builder|PaudPetugasBerkas whereCreatedBy($value)
 * @method static Builder|PaudPetugasBerkas whereUpdatedBy($value)
 */
class PaudPetugasBerkas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_petugas_berkas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_petugas_berkas_id';

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
        'k_berkas_petugas_paud' => 'int',
        'nama'                  => 'string',
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
        'paud_petugas_berkas_id',
        'paud_petugas_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_berkas_petugas_paud',
        'nama',
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
    public function mBerkasPetugasPaud()
    {
        return $this->belongsTo('App\Models\MBerkasPetugasPaud', 'k_berkas_petugas_paud', 'k_berkas_petugas_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudPetugas()
    {
        return $this->belongsTo('App\Models\PaudPetugas', 'paud_petugas_id', 'paud_petugas_id');
    }
}
