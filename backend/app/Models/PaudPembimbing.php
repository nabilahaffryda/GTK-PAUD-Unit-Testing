<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudPembimbing
 *
 * @property int $paud_pembimbing_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_verval_paud
 * @property null|Carbon $wkt_ajuan
 * @property null|Carbon $wkt_verval
 * @property null|int $k_kecamatan
 * @property null|int $k_kualifikasi
 * @property null|string $prodi
 * @property null|string $lulusan
 * @property null|string $instansi_nama
 * @property null|string $instansi_jabatan
 * @property null|string $instansi_alamat
 * @property null|int $instansi_k_propinsi
 * @property null|int $instansi_k_kota
 * @property null|string $instansi_kodepos
 * @property null|int $is_diklat_dasar
 * @property null|string $pengalaman
 * @property null|string $akun_id_verval
 * @property null|string $alasan
 * @property null|string $catatan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $admin_id
 *
 * @property-read Akun $akun
 * @property-read MVervalPaud $mVervalPaud
 * @property-read Collection|PaudPembimbingBerkas[] $paudPembimbingBerkases
 *
 * @method static Builder|PaudPembimbing wherePaudPembimbingId($value)
 * @method static Builder|PaudPembimbing whereAkunId($value)
 * @method static Builder|PaudPembimbing whereTahun($value)
 * @method static Builder|PaudPembimbing whereAngkatan($value)
 * @method static Builder|PaudPembimbing whereKVervalPaud($value)
 * @method static Builder|PaudPembimbing whereWktAjuan($value)
 * @method static Builder|PaudPembimbing whereWktVerval($value)
 * @method static Builder|PaudPembimbing whereKKecamatan($value)
 * @method static Builder|PaudPembimbing whereKKualifikasi($value)
 * @method static Builder|PaudPembimbing whereProdi($value)
 * @method static Builder|PaudPembimbing whereLulusan($value)
 * @method static Builder|PaudPembimbing whereInstansiNama($value)
 * @method static Builder|PaudPembimbing whereInstansiJabatan($value)
 * @method static Builder|PaudPembimbing whereInstansiAlamat($value)
 * @method static Builder|PaudPembimbing whereInstansiKPropinsi($value)
 * @method static Builder|PaudPembimbing whereInstansiKKota($value)
 * @method static Builder|PaudPembimbing whereInstansiKodepos($value)
 * @method static Builder|PaudPembimbing whereIsDiklatDasar($value)
 * @method static Builder|PaudPembimbing wherePengalaman($value)
 * @method static Builder|PaudPembimbing whereAkunIdVerval($value)
 * @method static Builder|PaudPembimbing whereAlasan($value)
 * @method static Builder|PaudPembimbing whereCatatan($value)
 * @method static Builder|PaudPembimbing whereCreatedAt($value)
 * @method static Builder|PaudPembimbing whereUpdatedAt($value)
 * @method static Builder|PaudPembimbing whereAdminId($value)
 */
class PaudPembimbing extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_pembimbing';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_pembimbing_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'akun_id'             => 'string',
        'tahun'               => 'int',
        'angkatan'            => 'int',
        'k_verval_paud'       => 'int',
        'wkt_ajuan'           => 'datetime',
        'wkt_verval'          => 'datetime',
        'k_kecamatan'         => 'int',
        'k_kualifikasi'       => 'int',
        'prodi'               => 'string',
        'lulusan'             => 'string',
        'instansi_nama'       => 'string',
        'instansi_jabatan'    => 'string',
        'instansi_alamat'     => 'string',
        'instansi_k_propinsi' => 'int',
        'instansi_k_kota'     => 'int',
        'instansi_kodepos'    => 'string',
        'is_diklat_dasar'     => 'int',
        'pengalaman'          => 'string',
        'akun_id_verval'      => 'string',
        'alasan'              => 'string',
        'catatan'             => 'string',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
        'admin_id'            => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_pembimbing_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'wkt_ajuan',
        'wkt_verval',
        'k_kecamatan',
        'k_kualifikasi',
        'prodi',
        'lulusan',
        'instansi_nama',
        'instansi_jabatan',
        'instansi_alamat',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_kodepos',
        'is_diklat_dasar',
        'pengalaman',
        'akun_id_verval',
        'alasan',
        'catatan',
        'admin_id',
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
    public function mVervalPaud()
    {
        return $this->belongsTo('App\Models\MVervalPaud', 'k_verval_paud', 'k_verval_paud');
    }

    /**
     * @return HasMany
     */
    public function paudPembimbingBerkases()
    {
        return $this->hasMany('App\Models\PaudPembimbingBerkas', 'paud_pembimbing_id', 'paud_pembimbing_id');
    }
}
