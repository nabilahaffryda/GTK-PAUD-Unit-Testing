<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\PaudTahapan
 *
 * @property int $paud_tahapan_id
 * @property null|int $tahun
 * @property null|int $angkatan
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
 *
 * @property-read Collection|PaudDiklat[] $paudDiklats
 *
 * @method static Builder|PaudTahapan wherePaudTahapanId($value)
 * @method static Builder|PaudTahapan whereTahun($value)
 * @method static Builder|PaudTahapan whereAngkatan($value)
 * @method static Builder|PaudTahapan whereTglDaftarMulai($value)
 * @method static Builder|PaudTahapan whereTglDaftarSelesai($value)
 * @method static Builder|PaudTahapan whereTglDiklatMulai($value)
 * @method static Builder|PaudTahapan whereTglDiklatSelesai($value)
 * @method static Builder|PaudTahapan whereTglTugasMulai($value)
 * @method static Builder|PaudTahapan whereTglTugasSelesai($value)
 * @method static Builder|PaudTahapan whereCreatedAt($value)
 * @method static Builder|PaudTahapan whereUpdatedAt($value)
 * @method static Builder|PaudTahapan whereCreatedBy($value)
 * @method static Builder|PaudTahapan whereUpdatedBy($value)
 */
class PaudTahapan extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_tahapan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_tahapan_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tahun'              => 'int',
        'angkatan'           => 'int',
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
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_tahapan_id',
        'tahun',
        'angkatan',
        'tgl_daftar_mulai',
        'tgl_daftar_selesai',
        'tgl_diklat_mulai',
        'tgl_diklat_selesai',
        'tgl_tugas_mulai',
        'tgl_tugas_selesai',
        'created_by',
        'updated_by',
    ];

    /**
     * @return HasMany
     */
    public function paudDiklats()
    {
        return $this->hasMany('App\Models\PaudDiklat', 'paud_tahapan_id', 'paud_tahapan_id');
    }
}
