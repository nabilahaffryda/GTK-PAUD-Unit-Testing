<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//region ### Additional namespace #
//endregion

/**
 * App\Models\MKecamatan
 *
 * @property integer $k_kecamatan
 * @property integer $k_kota
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|string $kode_dapodik
 * @property null|integer $kode_dagri
 * @property integer $is_aktif
 *
 * @property-read Collection|AjuanNik[] $ajuanNiks
 * @property-read Collection|Akun[] $akunInstansiKs
 * @property-read Collection|GpmPraktisiRegistrasi[] $gpmPraktisiRegistrasis
 * @property-read Collection|Komunitas[] $komunitases
 * @property-read Collection|MKelurahan[] $mKelurahans
 * @property-read Collection|MerdekaOrganisasi[] $merdekaOrganisasis
 * @property-read Collection|MerdekaRelawan[] $merdekaRelawans
 * @property-read Collection|PaudKelas[] $paudKelases
 * @property-read Collection|PaudPetugas[] $paudPetugases
 * @property-read Collection|Pb[] $pbs
 * @property-read Collection|PpgInstrukturRegistrasi[] $ppgInstrukturRegistrasis
 * @property-read Collection|Ptk[] $ptks
 * @property-read Collection|RayonWilayahK13[] $rayonWilayahK13s
 * @property-read Collection|SekolahDapodik[] $sekolahDapodiks
 * @property-read Collection|Sekolah[] $sekolahs
 * @property-read Collection|StudiKtp[] $studiKtps
 * @property-read Collection|VcSasaran[] $vcSasarans
 * @property-read MKota $mKota
 *
 * @method static Builder|MKecamatan whereKKecamatan($value)
 * @method static Builder|MKecamatan whereKKota($value)
 * @method static Builder|MKecamatan whereSingkat($value)
 * @method static Builder|MKecamatan whereKeterangan($value)
 * @method static Builder|MKecamatan whereKodeDapodik($value)
 * @method static Builder|MKecamatan whereKodeDagri($value)
 * @method static Builder|MKecamatan whereIsAktif($value)
 *
 * @method static Builder|MKecamatan query()
 *
 * @method static Collection|MKecamatan[]     all($columns = ['*'])
 * @method static MKecamatan|null             find($id, $columns = ['*'])
 * @method static Collection|MKecamatan[]     findMany($ids, $columns = ['*'])
 * @method static MKecamatan                  findOrNew($id, $columns = ['*'])
 * @method static MKecamatan                  findOrFail($id, $columns = ['*'])
 * @method static MKecamatan|null             first($columns = ['*'])
 * @method static MKecamatan                  firstOrFail($columns = ['*'])
 * @method static MKecamatan                  firstOrNew($attributes, $values = [])
 * @method static MKecamatan                  firstOrCreate($attributes, $values = ['*'])
 * @method static MKecamatan                  updateOrCreate($attributes, $values = ['*'])
 * @method static Collection|MKecamatan[]     get($columns = ['*'])
 */
class MKecamatan extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_kecamatan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_kecamatan';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'k_kecamatan',
        'k_kota',
        'singkat',
        'keterangan',
        'kode_dapodik',
        'kode_dagri',
        'is_aktif',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'k_kecamatan'  => 'integer',
        'k_kota'       => 'integer',
        'singkat'      => 'string',
        'keterangan'   => 'string',
        'kode_dapodik' => 'string',
        'kode_dagri'   => 'integer',
        'is_aktif'     => 'integer',
    ];

    /**
     * @return HasMany|Builder|AjuanNik
     */
    public function ajuanNiks()
    {
        return $this->hasMany('App\Models\AjuanNik', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|Akun
     */
    public function akunInstansiKs()
    {
        return $this->hasMany('App\Models\Akun', 'instansi_k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|GpmPraktisiRegistrasi
     */
    public function gpmPraktisiRegistrasis()
    {
        return $this->hasMany('App\Models\GpmPraktisiRegistrasi', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|Komunitas
     */
    public function komunitases()
    {
        return $this->hasMany('App\Models\Komunitas', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|MKelurahan
     */
    public function mKelurahans()
    {
        return $this->hasMany('App\Models\MKelurahan', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|MerdekaOrganisasi
     */
    public function merdekaOrganisasis()
    {
        return $this->hasMany('App\Models\MerdekaOrganisasi', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|MerdekaRelawan
     */
    public function merdekaRelawans()
    {
        return $this->hasMany('App\Models\MerdekaRelawan', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|PaudKelas
     */
    public function paudKelases()
    {
        return $this->hasMany('App\Models\PaudKelas', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|PaudPetugas
     */
    public function paudPetugases()
    {
        return $this->hasMany('App\Models\PaudPetugas', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|Pb
     */
    public function pbs()
    {
        return $this->hasMany('App\Models\Pb', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|PpgInstrukturRegistrasi
     */
    public function ppgInstrukturRegistrasis()
    {
        return $this->hasMany('App\Models\PpgInstrukturRegistrasi', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|Ptk
     */
    public function ptks()
    {
        return $this->hasMany('App\Models\Ptk', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|RayonWilayahK13
     */
    public function rayonWilayahK13s()
    {
        return $this->hasMany('App\Models\RayonWilayahK13', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|Sekolah
     */
    public function sekolahs()
    {
        return $this->hasMany('App\Models\Sekolah', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|SekolahDapodik
     */
    public function sekolahDapodiks()
    {
        return $this->hasMany('App\Models\SekolahDapodik', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|StudiKtp
     */
    public function studiKtps()
    {
        return $this->hasMany('App\Models\StudiKtp', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return HasMany|Builder|VcSasaran
     */
    public function vcSasarans()
    {
        return $this->hasMany('App\Models\VcSasaran', 'k_kecamatan', 'k_kecamatan');
    }

    /**
     * @return BelongsTo|Builder|MKota
     */
    public function mKota()
    {
        return $this->belongsTo('App\Models\MKota', 'k_kota', 'k_kota');
    }

    //region ### User defined function #
    //endregion
}
