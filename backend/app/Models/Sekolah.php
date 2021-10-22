<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Sekolah
 *
 * @property int $sekolah_id
 * @property string $nama
 * @property null|string $alamat
 * @property null|string $is_negeri
 * @property null|int $k_naungan
 * @property null|int $k_jenis_sekolah
 * @property int $k_jenjang
 * @property int $k_propinsi
 * @property int $k_kota
 * @property null|int $k_kecamatan
 * @property null|string $kecamatan
 * @property null|string $kelurahan
 * @property null|string $npsn
 * @property int $is_dinas
 * @property string $is_aktif
 * @property null|string $sekolah_id_dapodik
 * @property null|float $latitude
 * @property null|float $longitude
 * @property null|int $kebutuhan_khusus_id
 * @property null|int $k_bentuk_pendidikan
 * @property null|string $sektor_vokasi
 * @property null|Carbon $last_sync
 *
 * @property-read Collection|PtkSekolah[] $ptkSekolahs
 *
 * @method static Builder|Sekolah whereSekolahId($value)
 * @method static Builder|Sekolah whereNama($value)
 * @method static Builder|Sekolah whereAlamat($value)
 * @method static Builder|Sekolah whereIsNegeri($value)
 * @method static Builder|Sekolah whereKNaungan($value)
 * @method static Builder|Sekolah whereKJenisSekolah($value)
 * @method static Builder|Sekolah whereKJenjang($value)
 * @method static Builder|Sekolah whereKPropinsi($value)
 * @method static Builder|Sekolah whereKKota($value)
 * @method static Builder|Sekolah whereKKecamatan($value)
 * @method static Builder|Sekolah whereKecamatan($value)
 * @method static Builder|Sekolah whereKelurahan($value)
 * @method static Builder|Sekolah whereNpsn($value)
 * @method static Builder|Sekolah whereIsDinas($value)
 * @method static Builder|Sekolah whereIsAktif($value)
 * @method static Builder|Sekolah whereSekolahIdDapodik($value)
 * @method static Builder|Sekolah whereLatitude($value)
 * @method static Builder|Sekolah whereLongitude($value)
 * @method static Builder|Sekolah whereKebutuhanKhususId($value)
 * @method static Builder|Sekolah whereKBentukPendidikan($value)
 * @method static Builder|Sekolah whereSektorVokasi($value)
 * @method static Builder|Sekolah whereLastSync($value)
 */
class Sekolah extends Eloquent
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sekolah';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'sekolah_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'nama'                => 'string',
        'alamat'              => 'string',
        'is_negeri'           => 'string',
        'k_naungan'           => 'int',
        'k_jenis_sekolah'     => 'int',
        'k_jenjang'           => 'int',
        'k_propinsi'          => 'int',
        'k_kota'              => 'int',
        'k_kecamatan'         => 'int',
        'kecamatan'           => 'string',
        'kelurahan'           => 'string',
        'npsn'                => 'string',
        'is_dinas'            => 'int',
        'is_aktif'            => 'string',
        'sekolah_id_dapodik'  => 'string',
        'latitude'            => 'float',
        'longitude'           => 'float',
        'kebutuhan_khusus_id' => 'int',
        'k_bentuk_pendidikan' => 'int',
        'sektor_vokasi'       => 'string',
        'last_sync'           => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'sekolah_id',
        'nama',
        'alamat',
        'is_negeri',
        'k_naungan',
        'k_jenis_sekolah',
        'k_jenjang',
        'k_propinsi',
        'k_kota',
        'k_kecamatan',
        'kecamatan',
        'kelurahan',
        'npsn',
        'is_dinas',
        'is_aktif',
        'sekolah_id_dapodik',
        'latitude',
        'longitude',
        'kebutuhan_khusus_id',
        'k_bentuk_pendidikan',
        'sektor_vokasi',
        'last_sync',
    ];

    /**
     * @return HasMany
     */
    public function ptkSekolahs()
    {
        return $this->hasMany('App\Models\PtkSekolah', 'sekolah_id', 'sekolah_id');
    }
}
