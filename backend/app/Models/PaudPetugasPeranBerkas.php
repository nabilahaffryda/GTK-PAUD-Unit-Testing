<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\PaudPetugasPeranBerkas
 *
 * @property int $paud_petugas_peran_berkas_id
 * @property int $paud_petugas_peran_id
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
 * @method static Builder|PaudPetugasPeranBerkas wherePaudPetugasPeranBerkasId($value)
 * @method static Builder|PaudPetugasPeranBerkas wherePaudPetugasPeranId($value)
 * @method static Builder|PaudPetugasPeranBerkas whereAkunId($value)
 * @method static Builder|PaudPetugasPeranBerkas whereTahun($value)
 * @method static Builder|PaudPetugasPeranBerkas whereAngkatan($value)
 * @method static Builder|PaudPetugasPeranBerkas whereKBerkasPetugasPaud($value)
 * @method static Builder|PaudPetugasPeranBerkas whereNama($value)
 * @method static Builder|PaudPetugasPeranBerkas whereFile($value)
 * @method static Builder|PaudPetugasPeranBerkas whereKeterangan($value)
 * @method static Builder|PaudPetugasPeranBerkas whereCreatedAt($value)
 * @method static Builder|PaudPetugasPeranBerkas whereUpdatedAt($value)
 * @method static Builder|PaudPetugasPeranBerkas whereCreatedBy($value)
 * @method static Builder|PaudPetugasPeranBerkas whereUpdatedBy($value)
 */
class PaudPetugasPeranBerkas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_petugas_peran_berkas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_petugas_peran_berkas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_petugas_peran_id' => 'int',
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
        'paud_petugas_peran_berkas_id',
        'paud_petugas_peran_id',
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
}
