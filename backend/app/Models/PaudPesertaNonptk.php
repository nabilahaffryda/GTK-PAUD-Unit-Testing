<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudPesertaNonptk
 *
 * @property int $paud_peserta_nonptk_id
 * @property int $paud_instansi_id
 * @property int $instansi_id
 * @property null|string $nama
 * @property string $nik
 * @property null|string $tmp_lahir
 * @property null|Carbon $tgl_lahir
 * @property null|string $kelamin
 * @property null|string $email
 * @property null|string $no_hp
 * @property null|string $alamat
 * @property null|int $k_propinsi
 * @property null|int $k_kota
 * @property null|int $k_kecamatan
 * @property null|string $unit_kerja
 * @property null|int $k_diklat_paud
 * @property null|int $k_jenjang_diklat_paud
 * @property null|string $sertifikat_nama
 * @property null|string $sertifikat_file
 * @property null|string $ktp_nama
 * @property null|string $ktp_file
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 *
 * @property-read null|string $sertifikat_url
 * @property-read null|string $ktp_url
 *
 * @property-read Instansi $instansi
 * @property-read MDiklatPaud $mDiklatPaud
 * @property-read MJenjangDiklatPaud $mJenjangDiklatPaud
 * @property-read MKecamatan $mKecamatan
 * @property-read MKota $mKota
 * @property-read MPropinsi $mPropinsi
 * @property-read PaudInstansi $paudInstansi
 * @property-read Collection|PaudKelasPesertaLuring[] $paudKelasPesertaLurings
 *
 * @method static Builder|PaudPesertaNonptk wherePaudPesertaNonptkId($value)
 * @method static Builder|PaudPesertaNonptk wherePaudInstansiId($value)
 * @method static Builder|PaudPesertaNonptk whereInstansiId($value)
 * @method static Builder|PaudPesertaNonptk whereNama($value)
 * @method static Builder|PaudPesertaNonptk whereNik($value)
 * @method static Builder|PaudPesertaNonptk whereTmpLahir($value)
 * @method static Builder|PaudPesertaNonptk whereTglLahir($value)
 * @method static Builder|PaudPesertaNonptk whereKelamin($value)
 * @method static Builder|PaudPesertaNonptk whereEmail($value)
 * @method static Builder|PaudPesertaNonptk whereNoHp($value)
 * @method static Builder|PaudPesertaNonptk whereAlamat($value)
 * @method static Builder|PaudPesertaNonptk whereKPropinsi($value)
 * @method static Builder|PaudPesertaNonptk whereKKota($value)
 * @method static Builder|PaudPesertaNonptk whereKKecamatan($value)
 * @method static Builder|PaudPesertaNonptk whereUnitKerja($value)
 * @method static Builder|PaudPesertaNonptk whereKDiklatPaud($value)
 * @method static Builder|PaudPesertaNonptk whereKJenjangDiklatPaud($value)
 * @method static Builder|PaudPesertaNonptk whereSertifikatNama($value)
 * @method static Builder|PaudPesertaNonptk whereSertifikatFile($value)
 * @method static Builder|PaudPesertaNonptk whereKtpNama($value)
 * @method static Builder|PaudPesertaNonptk whereKtpFile($value)
 * @method static Builder|PaudPesertaNonptk whereCreatedAt($value)
 * @method static Builder|PaudPesertaNonptk whereUpdatedAt($value)
 * @method static Builder|PaudPesertaNonptk whereCreatedBy($value)
 * @method static Builder|PaudPesertaNonptk whereUpdatedBy($value)
 */
class PaudPesertaNonptk extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_peserta_nonptk';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_peserta_nonptk_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_instansi_id' => 'int',
        'instansi_id' => 'int',
        'nama' => 'string',
        'nik' => 'string',
        'tmp_lahir' => 'string',
        'tgl_lahir' => 'date:Y-m-d',
        'kelamin' => 'string',
        'email' => 'string',
        'no_hp' => 'string',
        'alamat' => 'string',
        'k_propinsi' => 'int',
        'k_kota' => 'int',
        'k_kecamatan' => 'int',
        'unit_kerja' => 'string',
        'k_diklat_paud' => 'int',
        'k_jenjang_diklat_paud' => 'int',
        'sertifikat_nama' => 'string',
        'sertifikat_file' => 'string',
        'ktp_nama' => 'string',
        'ktp_file' => 'string',
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
        'sertifikat_url',
        'ktp_url',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_peserta_nonptk_id',
        'paud_instansi_id',
        'instansi_id',
        'nama',
        'nik',
        'tmp_lahir',
        'tgl_lahir',
        'kelamin',
        'email',
        'no_hp',
        'alamat',
        'k_propinsi',
        'k_kota',
        'k_kecamatan',
        'unit_kerja',
        'k_diklat_paud',
        'k_jenjang_diklat_paud',
        'sertifikat_nama',
        'sertifikat_file',
        'ktp_nama',
        'ktp_file',
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
    public function mKecamatan()
    {
        return $this->belongsTo('App\Models\MKecamatan', 'k_kecamatan', 'k_kecamatan');
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
    public function paudKelasPesertaLurings()
    {
        return $this->hasMany('App\Models\PaudKelasPesertaLuring', 'paud_peserta_nonptk_id', 'paud_peserta_nonptk_id');
    }

    public function getSertifikatUrlAttribute()
    {
        return $this->sertifikat_file ? sprintf("%s/%s", config('filesystems.disks.peserta-nonptk.url'), $this->sertifikat_file) : null;
    }

    public function getKtpUrlAttribute()
    {
        return $this->ktp_file ? sprintf("%s/%s", config('filesystems.disks.peserta-nonptk.url'), $this->ktp_file) : null;
    }
}
