<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * App\Models\PaudKelasLuring
 *
 * @property int $paud_kelas_luring_id
 * @property null|int $paud_diklat_luring_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property null|string $nama
 * @property null|int $paud_mapel_kelas_id
 * @property null|string $deskripsi
 * @property null|int $k_kecamatan
 * @property null|int $k_kelurahan
 * @property null|int $jml_pengajar
 * @property null|int $jml_pembimbing
 * @property null|int $k_verval_paud
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
 *
 * @property-read null|string $url_jadwal
 *
 * @property-read MKecamatan $mKecamatan
 * @property-read MKelurahan $mKelurahan
 * @property-read MVervalPaud $mVervalPaud
 * @property-read PaudDiklatLuring $paudDiklatLuring
 * @property-read PaudMapelKelas $paudMapelKelas
 * @property-read Collection|PaudKelasPesertaLuring[] $paudKelasPesertaLurings
 * @property-read Collection|PaudKelasPetugasLuring[] $paudKelasPetugasLurings
 *
 * @method static Builder|PaudKelasLuring wherePaudKelasLuringId($value)
 * @method static Builder|PaudKelasLuring wherePaudDiklatLuringId($value)
 * @method static Builder|PaudKelasLuring whereTahun($value)
 * @method static Builder|PaudKelasLuring whereAngkatan($value)
 * @method static Builder|PaudKelasLuring whereNama($value)
 * @method static Builder|PaudKelasLuring wherePaudMapelKelasId($value)
 * @method static Builder|PaudKelasLuring whereDeskripsi($value)
 * @method static Builder|PaudKelasLuring whereKKecamatan($value)
 * @method static Builder|PaudKelasLuring whereKKelurahan($value)
 * @method static Builder|PaudKelasLuring whereJmlPengajar($value)
 * @method static Builder|PaudKelasLuring whereJmlPembimbing($value)
 * @method static Builder|PaudKelasLuring whereKVervalPaud($value)
 * @method static Builder|PaudKelasLuring whereWktAjuan($value)
 * @method static Builder|PaudKelasLuring whereAkunIdVerval($value)
 * @method static Builder|PaudKelasLuring whereFileJadwal($value)
 * @method static Builder|PaudKelasLuring whereAlasan($value)
 * @method static Builder|PaudKelasLuring whereCatatan($value)
 * @method static Builder|PaudKelasLuring whereWktVerval($value)
 * @method static Builder|PaudKelasLuring whereCreatedAt($value)
 * @method static Builder|PaudKelasLuring whereUpdatedAt($value)
 * @method static Builder|PaudKelasLuring whereCreatedBy($value)
 * @method static Builder|PaudKelasLuring whereUpdatedBy($value)
 */
class PaudKelasLuring extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_kelas_luring';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_kelas_luring_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_diklat_luring_id' => 'int',
        'tahun' => 'int',
        'angkatan' => 'int',
        'nama' => 'string',
        'paud_mapel_kelas_id' => 'int',
        'deskripsi' => 'string',
        'k_kecamatan' => 'int',
        'k_kelurahan' => 'int',
        'jml_pengajar' => 'int',
        'jml_pembimbing' => 'int',
        'k_verval_paud' => 'int',
        'wkt_ajuan' => 'datetime',
        'akun_id_verval' => 'string',
        'file_jadwal' => 'string',
        'alasan' => 'string',
        'catatan' => 'string',
        'wkt_verval' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'string',
        'updated_by' => 'string',
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
        'paud_kelas_luring_id',
        'paud_diklat_luring_id',
        'tahun',
        'angkatan',
        'nama',
        'paud_mapel_kelas_id',
        'deskripsi',
        'k_kecamatan',
        'k_kelurahan',
        'jml_pengajar',
        'jml_pembimbing',
        'k_verval_paud',
        'wkt_ajuan',
        'akun_id_verval',
        'file_jadwal',
        'alasan',
        'catatan',
        'wkt_verval',
        'created_by',
        'updated_by',
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
    public function paudDiklatLuring()
    {
        return $this->belongsTo('App\Models\PaudDiklatLuring', 'paud_diklat_luring_id', 'paud_diklat_luring_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPesertaLurings()
    {
        return $this->hasMany('App\Models\PaudKelasPesertaLuring', 'paud_kelas_luring_id', 'paud_kelas_luring_id');
    }

    /**
     * @return HasMany
     */
    public function paudKelasPetugasLurings()
    {
        return $this->hasMany('App\Models\PaudKelasPetugasLuring', 'paud_kelas_luring_id', 'paud_kelas_luring_id');
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
