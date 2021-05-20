<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudPetugasPeran
 *
 * @property int $paud_petugas_peran_id
 * @property null|int $paud_petugas_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_verval_paud
 * @property null|Carbon $wkt_ajuan
 * @property null|Carbon $wkt_verval
 * @property null|string $akun_id_verval
 * @property null|string $alasan
 * @property null|string $catatan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read Akun $akun
 * @property-read Akun $akunVerval
 * @property-read MVervalPaud $mVervalPaud
 * @property-read PaudPetugas $paudPetugas
 *
 * @method static Builder|PaudPetugasPeran wherePaudPetugasPeranId($value)
 * @method static Builder|PaudPetugasPeran wherePaudPetugasId($value)
 * @method static Builder|PaudPetugasPeran whereAkunId($value)
 * @method static Builder|PaudPetugasPeran whereTahun($value)
 * @method static Builder|PaudPetugasPeran whereAngkatan($value)
 * @method static Builder|PaudPetugasPeran whereKVervalPaud($value)
 * @method static Builder|PaudPetugasPeran whereWktAjuan($value)
 * @method static Builder|PaudPetugasPeran whereWktVerval($value)
 * @method static Builder|PaudPetugasPeran whereAkunIdVerval($value)
 * @method static Builder|PaudPetugasPeran whereAlasan($value)
 * @method static Builder|PaudPetugasPeran whereCatatan($value)
 * @method static Builder|PaudPetugasPeran whereCreatedAt($value)
 * @method static Builder|PaudPetugasPeran whereUpdatedAt($value)
 * @method static Builder|PaudPetugasPeran whereCreatedBy($value)
 * @method static Builder|PaudPetugasPeran whereUpdatedBy($value)
 */
class PaudPetugasPeran extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_petugas_peran';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_petugas_peran_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_petugas_id' => 'int',
        'akun_id'         => 'string',
        'tahun'           => 'int',
        'angkatan'        => 'int',
        'k_verval_paud'   => 'int',
        'wkt_ajuan'       => 'datetime',
        'wkt_verval'      => 'datetime',
        'akun_id_verval'  => 'string',
        'alasan'          => 'string',
        'catatan'         => 'string',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
        'created_by'      => 'string',
        'updated_by'      => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_petugas_peran_id',
        'paud_petugas_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'wkt_ajuan',
        'wkt_verval',
        'akun_id_verval',
        'alasan',
        'catatan',
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
    public function akunVerval()
    {
        return $this->belongsTo('App\Models\Akun', 'akun_id_verval', 'akun_id');
    }

    /**
     * @return BelongsTo
     */
    public function mVervalPaud()
    {
        return $this->belongsTo('App\Models\MVervalPaud', 'k_verval_paud', 'k_verval_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudPetugas()
    {
        return $this->belongsTo('App\Models\PaudPetugas', 'paud_petugas_id', 'paud_petugas_id');
    }

    public function isVervalKandidat()
    {
        return $this->k_verval_paud == MVervalPaud::KANDIDAT;
    }

    public function isVervalRevisi()
    {
        return $this->k_verval_paud == MVervalPaud::REVISI;
    }

    public function isVervalDiajukan()
    {
        return $this->k_verval_paud == MVervalPaud::DIAJUKAN;
    }
}
