<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * App\Models\PaudDiklatLuring
 *
 * @property int $paud_diklat_luring_id
 * @property null|int $paud_instansi_id
 * @property null|int $instansi_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|int $k_diklat_paud
 * @property null|int $k_jenjang_diklat_paud
 * @property null|string $nama
 * @property null|string $singkatan
 * @property null|string $deskripsi
 * @property null|int $k_propinsi
 * @property null|int $k_kota
 * @property null|int $jml_kelas
 * @property null|string $catatan
 * @property null|Carbon $tgl_mulai
 * @property null|Carbon $tgl_selesai
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read Instansi $instansi
 * @property-read MDiklatPaud $mDiklatPaud
 * @property-read MJenjangDiklatPaud $mJenjangDiklatPaud
 * @property-read MKota $mKota
 * @property-read MPropinsi $mPropinsi
 * @property-read PaudInstansi $paudInstansi
 * @property-read Collection|PaudKelasLuring[] $paudKelasLurings
 *
 * @method static Builder|PaudDiklatLuring wherePaudDiklatLuringId($value)
 * @method static Builder|PaudDiklatLuring wherePaudInstansiId($value)
 * @method static Builder|PaudDiklatLuring whereInstansiId($value)
 * @method static Builder|PaudDiklatLuring whereTahun($value)
 * @method static Builder|PaudDiklatLuring whereAngkatan($value)
 * @method static Builder|PaudDiklatLuring whereKDiklatPaud($value)
 * @method static Builder|PaudDiklatLuring whereKJenjangDiklatPaud($value)
 * @method static Builder|PaudDiklatLuring whereNama($value)
 * @method static Builder|PaudDiklatLuring whereSingkatan($value)
 * @method static Builder|PaudDiklatLuring whereDeskripsi($value)
 * @method static Builder|PaudDiklatLuring whereKPropinsi($value)
 * @method static Builder|PaudDiklatLuring whereKKota($value)
 * @method static Builder|PaudDiklatLuring whereJmlKelas($value)
 * @method static Builder|PaudDiklatLuring whereCatatan($value)
 * @method static Builder|PaudDiklatLuring whereTglMulai($value)
 * @method static Builder|PaudDiklatLuring whereTglSelesai($value)
 * @method static Builder|PaudDiklatLuring whereCreatedAt($value)
 * @method static Builder|PaudDiklatLuring whereUpdatedAt($value)
 * @method static Builder|PaudDiklatLuring whereCreatedBy($value)
 * @method static Builder|PaudDiklatLuring whereUpdatedBy($value)
 */
class PaudDiklatLuring extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_diklat_luring';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_diklat_luring_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_instansi_id' => 'int',
        'instansi_id' => 'int',
        'tahun' => 'int',
        'angkatan' => 'int',
        'k_diklat_paud' => 'int',
        'k_jenjang_diklat_paud' => 'int',
        'nama' => 'string',
        'singkatan' => 'string',
        'deskripsi' => 'string',
        'k_propinsi' => 'int',
        'k_kota' => 'int',
        'jml_kelas' => 'int',
        'catatan' => 'string',
        'tgl_mulai' => 'date:Y-m-d',
        'tgl_selesai' => 'date:Y-m-d',
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
        'paud_diklat_luring_id',
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_diklat_paud',
        'k_jenjang_diklat_paud',
        'nama',
        'singkatan',
        'deskripsi',
        'k_propinsi',
        'k_kota',
        'jml_kelas',
        'catatan',
        'tgl_mulai',
        'tgl_selesai',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function instansi()
    {
        return $this->belongsTo('App\Models\Instansi', 'instansi_id', 'instansi_id');
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
    public function mJenjangDiklatPaud()
    {
        return $this->belongsTo('App\Models\MJenjangDiklatPaud', 'k_jenjang_diklat_paud', 'k_jenjang_diklat_paud');
    }

    /**
     * @return BelongsTo
     */
    public function mKota()
    {
        return $this->belongsTo('App\Models\MKota', 'k_kota', 'k_kota');
    }

    /**
     * @return BelongsTo
     */
    public function mPropinsi()
    {
        return $this->belongsTo('App\Models\MPropinsi', 'k_propinsi', 'k_propinsi');
    }

    /**
     * @return BelongsTo
     */
    public function paudInstansi()
    {
        return $this->belongsTo('App\Models\PaudInstansi', 'paud_instansi_id', 'paud_instansi_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasLurings()
    {
        return $this->hasMany('App\Models\PaudKelasLuring', 'paud_diklat_luring_id', 'paud_diklat_luring_id');
    }
}
