<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudKelas
 *
 * @property int $paud_kelas_id
 * @property null|int $paud_diklat_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|string $nama
 * @property null|int $k_kecamatan
 * @property null|int $k_kelurahan
 * @property null|string $sekolah_kbl
 * @property null|string $sekolah_alamat
 * @property null|int $jml_pengajar
 * @property null|int $jml_pembimbing
 * @property null|Carbon $wkt_sinkron
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read PaudDiklat $paudDiklat
 * @property-read Collection|PaudKelasPeserta[] $paudKelasPesertas
 * @property-read Collection|PaudKelasPetugas[] $paudKelasPetugases
 *
 * @method static Builder|PaudKelas wherePaudKelasId($value)
 * @method static Builder|PaudKelas wherePaudDiklatId($value)
 * @method static Builder|PaudKelas whereTahun($value)
 * @method static Builder|PaudKelas whereAngkatan($value)
 * @method static Builder|PaudKelas whereNama($value)
 * @method static Builder|PaudKelas whereKKecamatan($value)
 * @method static Builder|PaudKelas whereKKelurahan($value)
 * @method static Builder|PaudKelas whereSekolahKbl($value)
 * @method static Builder|PaudKelas whereSekolahAlamat($value)
 * @method static Builder|PaudKelas whereJmlPengajar($value)
 * @method static Builder|PaudKelas whereJmlPembimbing($value)
 * @method static Builder|PaudKelas whereWktSinkron($value)
 * @method static Builder|PaudKelas whereCreatedAt($value)
 * @method static Builder|PaudKelas whereUpdatedAt($value)
 * @method static Builder|PaudKelas whereCreatedBy($value)
 * @method static Builder|PaudKelas whereUpdatedBy($value)
 */
class PaudKelas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_diklat_id' => 'int',
        'tahun'          => 'int',
        'angkatan'       => 'int',
        'nama'           => 'string',
        'k_kecamatan'    => 'int',
        'k_kelurahan'    => 'int',
        'sekolah_kbl'    => 'string',
        'sekolah_alamat' => 'string',
        'jml_pengajar'   => 'int',
        'jml_pembimbing' => 'int',
        'wkt_sinkron'    => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'created_by'     => 'string',
        'updated_by'     => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_kelas_id',
        'paud_diklat_id',
        'tahun',
        'angkatan',
        'nama',
        'k_kecamatan',
        'k_kelurahan',
        'sekolah_kbl',
        'sekolah_alamat',
        'jml_pengajar',
        'jml_pembimbing',
        'wkt_sinkron',
        'created_by',
        'updated_by',
    ];

    /**
     * @return BelongsTo
     */
    public function paudDiklat()
    {
        return $this->belongsTo('App\Models\PaudDiklat', 'paud_diklat_id', 'paud_diklat_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPesertas()
    {
        return $this->hasMany('App\Models\PaudKelasPeserta', 'paud_kelas_id', 'paud_kelas_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPetugases()
    {
        return $this->hasMany('App\Models\PaudKelasPetugas', 'paud_kelas_id', 'paud_kelas_id');
    }
}
