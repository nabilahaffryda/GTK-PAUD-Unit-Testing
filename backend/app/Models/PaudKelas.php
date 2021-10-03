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
 * @property null|int $paud_mapel_kelas_id
 * @property null|string $deskripsi
 * @property null|int $k_kecamatan
 * @property null|int $k_kelurahan
 * @property null|string $sekolah_kbl
 * @property null|string $sekolah_alamat
 * @property null|int $jml_pengajar
 * @property null|int $jml_pembimbing
 * @property null|int $k_verval_paud
 * @property null|Carbon $wkt_sinkron
 * @property null|Carbon $wkt_ajuan
 * @property null|string $akun_id_verval
 * @property null|string $file_jadwal
 * @property null|string $alasan
 * @property null|string $catatan
 * @property null|Carbon $wkt_verval
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|int $lms_kelas_id
 * @property null|string $lms_url
 * @property null|string $sync_status
 * @property null|Carbon $wkt_sync
 *
 * @property-read null|string $url_jadwal
 *
 * @property-read MKecamatan $mKecamatan
 * @property-read MKelurahan $mKelurahan
 * @property-read MVervalPaud $mVervalPaud
 * @property-read PaudDiklat $paudDiklat
 * @property-read PaudMapelKelas $paudMapelKelas
 * @property-read Collection|PaudKelasPeserta[] $paudKelasPesertas
 * @property-read Collection|PaudKelasPetugas[] $paudKelasPetugases
 *
 * @method static Builder|PaudKelas wherePaudKelasId($value)
 * @method static Builder|PaudKelas wherePaudDiklatId($value)
 * @method static Builder|PaudKelas whereTahun($value)
 * @method static Builder|PaudKelas whereAngkatan($value)
 * @method static Builder|PaudKelas whereNama($value)
 * @method static Builder|PaudKelas wherePaudMapelKelasId($value)
 * @method static Builder|PaudKelas whereDeskripsi($value)
 * @method static Builder|PaudKelas whereKKecamatan($value)
 * @method static Builder|PaudKelas whereKKelurahan($value)
 * @method static Builder|PaudKelas whereSekolahKbl($value)
 * @method static Builder|PaudKelas whereSekolahAlamat($value)
 * @method static Builder|PaudKelas whereJmlPengajar($value)
 * @method static Builder|PaudKelas whereJmlPembimbing($value)
 * @method static Builder|PaudKelas whereKVervalPaud($value)
 * @method static Builder|PaudKelas whereWktSinkron($value)
 * @method static Builder|PaudKelas whereWktAjuan($value)
 * @method static Builder|PaudKelas whereAkunIdVerval($value)
 * @method static Builder|PaudKelas whereFileJadwal($value)
 * @method static Builder|PaudKelas whereAlasan($value)
 * @method static Builder|PaudKelas whereCatatan($value)
 * @method static Builder|PaudKelas whereWktVerval($value)
 * @method static Builder|PaudKelas whereCreatedAt($value)
 * @method static Builder|PaudKelas whereUpdatedAt($value)
 * @method static Builder|PaudKelas whereCreatedBy($value)
 * @method static Builder|PaudKelas whereUpdatedBy($value)
 * @method static Builder|PaudKelas whereLmsKelasId($value)
 * @method static Builder|PaudKelas whereLmsUrl($value)
 * @method static Builder|PaudKelas whereSyncStatus($value)
 * @method static Builder|PaudKelas whereWktSync($value)
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
        'paud_diklat_id'      => 'int',
        'tahun'               => 'int',
        'angkatan'            => 'int',
        'nama'                => 'string',
        'paud_mapel_kelas_id' => 'int',
        'deskripsi'           => 'string',
        'k_kecamatan'         => 'int',
        'k_kelurahan'         => 'int',
        'sekolah_kbl'         => 'string',
        'sekolah_alamat'      => 'string',
        'jml_pengajar'        => 'int',
        'jml_pembimbing'      => 'int',
        'k_verval_paud'       => 'int',
        'wkt_sinkron'         => 'datetime',
        'wkt_ajuan'           => 'datetime',
        'akun_id_verval'      => 'string',
        'file_jadwal'         => 'string',
        'alasan'              => 'string',
        'catatan'             => 'string',
        'wkt_verval'          => 'datetime',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'created_by'          => 'string',
        'updated_by'          => 'string',
        'lms_kelas_id'        => 'int',
        'lms_url'             => 'string',
        'sync_status'         => 'string',
        'wkt_sync'            => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url_jadwal',
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
        'paud_mapel_kelas_id',
        'deskripsi',
        'k_kecamatan',
        'k_kelurahan',
        'sekolah_kbl',
        'sekolah_alamat',
        'jml_pengajar',
        'jml_pembimbing',
        'k_verval_paud',
        'wkt_sinkron',
        'wkt_ajuan',
        'akun_id_verval',
        'file_jadwal',
        'alasan',
        'catatan',
        'wkt_verval',
        'created_by',
        'updated_by',
        'lms_kelas_id',
        'lms_url',
        'sync_status',
        'wkt_sync',
    ];

    /**
     * @return BelongsTo
     */
    public function mKecamatan()
    {
        return $this->belongsTo('App\Models\MKecamatan', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return BelongsTo
     */
    public function mKelurahan()
    {
        return $this->belongsTo('App\Models\MKelurahan', 'k_kelurahan', 'k_kelurahan');
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

    /**
     * @return BelongsTo
     */
    public function paudMapelKelas()
    {
        return $this->belongsTo('App\Models\PaudMapelKelas', 'paud_mapel_kelas_id', 'paud_mapel_kelas_id');
    }

    public function getUrlJadwalAttribute()
    {
        return $this->file_jadwal ? sprintf("%s/%s", config('filesystems.disks.kelas-jadwal.url'), $this->file_jadwal) : null;
    }
}
