<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudDiklat
 *
 * @property int $paud_diklat_id
 * @property null|int $paud_instansi_id
 * @property null|int $instansi_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|int $k_verval_paud
 * @property null|Carbon $wkt_ajuan
 * @property null|Carbon $wkt_verval
 * @property null|int $paud_tahapan_id
 * @property null|int $k_propinsi
 * @property null|int $k_kota
 * @property null|int $jml_kelas
 * @property null|string $alasan
 * @property null|string $catatan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read Instansi $instansi
 * @property-read MKota $mKota
 * @property-read MPropinsi $mPropinsi
 * @property-read MVervalPaud $mVervalPaud
 * @property-read PaudInstansi $paudInstansi
 * @property-read PaudTahapan $paudTahapan
 * @property-read Collection|PaudKelas[] $paudKelases
 *
 * @method static Builder|PaudDiklat wherePaudDiklatId($value)
 * @method static Builder|PaudDiklat wherePaudInstansiId($value)
 * @method static Builder|PaudDiklat whereInstansiId($value)
 * @method static Builder|PaudDiklat whereTahun($value)
 * @method static Builder|PaudDiklat whereAngkatan($value)
 * @method static Builder|PaudDiklat whereKVervalPaud($value)
 * @method static Builder|PaudDiklat whereWktAjuan($value)
 * @method static Builder|PaudDiklat whereWktVerval($value)
 * @method static Builder|PaudDiklat wherePaudTahapanId($value)
 * @method static Builder|PaudDiklat whereKPropinsi($value)
 * @method static Builder|PaudDiklat whereKKota($value)
 * @method static Builder|PaudDiklat whereJmlKelas($value)
 * @method static Builder|PaudDiklat whereAlasan($value)
 * @method static Builder|PaudDiklat whereCatatan($value)
 * @method static Builder|PaudDiklat whereCreatedAt($value)
 * @method static Builder|PaudDiklat whereUpdatedAt($value)
 * @method static Builder|PaudDiklat whereCreatedBy($value)
 * @method static Builder|PaudDiklat whereUpdatedBy($value)
 */
class PaudDiklat extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_diklat';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_diklat_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_instansi_id' => 'int',
        'instansi_id'      => 'int',
        'tahun'            => 'int',
        'angkatan'         => 'int',
        'k_verval_paud'    => 'int',
        'wkt_ajuan'        => 'datetime',
        'wkt_verval'       => 'datetime',
        'paud_tahapan_id'  => 'int',
        'k_propinsi'       => 'int',
        'k_kota'           => 'int',
        'jml_kelas'        => 'int',
        'alasan'           => 'string',
        'catatan'          => 'string',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
        'created_by'       => 'string',
        'updated_by'       => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_diklat_id',
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'wkt_ajuan',
        'wkt_verval',
        'paud_tahapan_id',
        'k_propinsi',
        'k_kota',
        'jml_kelas',
        'alasan',
        'catatan',
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
    public function mVervalPaud()
    {
        return $this->belongsTo('App\Models\MVervalPaud', 'k_verval_paud', 'k_verval_paud');
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
    public function paudKelases()
    {
        return $this->hasMany('App\Models\PaudKelas', 'paud_diklat_id', 'paud_diklat_id');
    }

    /**
     * @return BelongsTo
     */
    public function paudTahapan()
    {
        return $this->belongsTo('App\Models\PaudTahapan', 'paud_tahapan_id', 'paud_tahapan_id');
    }
}
