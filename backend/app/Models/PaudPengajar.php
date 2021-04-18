<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudPengajar
 *
 * @property int $paud_pengajar_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_verval_paud
 * @property null|Carbon $wkt_ajuan
 * @property null|Carbon $wkt_verval
 * @property null|int $k_kualifikasi
 * @property null|string $prodi
 * @property null|string $lulusan
 * @property null|string $instansi_nama
 * @property null|string $instansi_jabatan
 * @property null|string $instansi_alamat
 * @property null|int $instansi_k_propinsi
 * @property null|int $instansi_k_kota
 * @property null|string $instansi_kodepos
 * @property null|int $k_pcp_paud
 * @property null|array $pengalaman
 * @property null|int $is_pembimbing
 * @property null|string $akun_id_verval
 * @property null|string $alasan
 * @property null|string $catatan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $admin_id
 *
 * @property-read Akun $akun
 * @property-read MPcpPaud $mPcpPaud
 * @property-read MVervalPaud $mVervalPaud
 * @property-read Collection|PaudPengajarBerkas[] $paudPengajarBerkases
 *
 * @method static Builder|PaudPengajar wherePaudPengajarId($value)
 * @method static Builder|PaudPengajar whereAkunId($value)
 * @method static Builder|PaudPengajar whereTahun($value)
 * @method static Builder|PaudPengajar whereAngkatan($value)
 * @method static Builder|PaudPengajar whereKVervalPaud($value)
 * @method static Builder|PaudPengajar whereWktAjuan($value)
 * @method static Builder|PaudPengajar whereWktVerval($value)
 * @method static Builder|PaudPengajar whereKKualifikasi($value)
 * @method static Builder|PaudPengajar whereProdi($value)
 * @method static Builder|PaudPengajar whereLulusan($value)
 * @method static Builder|PaudPengajar whereInstansiNama($value)
 * @method static Builder|PaudPengajar whereInstansiJabatan($value)
 * @method static Builder|PaudPengajar whereInstansiAlamat($value)
 * @method static Builder|PaudPengajar whereInstansiKPropinsi($value)
 * @method static Builder|PaudPengajar whereInstansiKKota($value)
 * @method static Builder|PaudPengajar whereInstansiKodepos($value)
 * @method static Builder|PaudPengajar whereKPcpPaud($value)
 * @method static Builder|PaudPengajar wherePengalaman($value)
 * @method static Builder|PaudPengajar whereIsPembimbing($value)
 * @method static Builder|PaudPengajar whereAkunIdVerval($value)
 * @method static Builder|PaudPengajar whereAlasan($value)
 * @method static Builder|PaudPengajar whereCatatan($value)
 * @method static Builder|PaudPengajar whereCreatedAt($value)
 * @method static Builder|PaudPengajar whereUpdatedAt($value)
 * @method static Builder|PaudPengajar whereAdminId($value)
 */
class PaudPengajar extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_pengajar';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_pengajar_id';

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
        'k_kualifikasi'       => 'int',
        'prodi'               => 'string',
        'lulusan'             => 'string',
        'instansi_nama'       => 'string',
        'instansi_jabatan'    => 'string',
        'instansi_alamat'     => 'string',
        'instansi_k_propinsi' => 'int',
        'instansi_k_kota'     => 'int',
        'instansi_kodepos'    => 'string',
        'k_pcp_paud'          => 'int',
        'pengalaman'          => 'array',
        'is_pembimbing'       => 'int',
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
        'paud_pengajar_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'wkt_ajuan',
        'wkt_verval',
        'k_kualifikasi',
        'prodi',
        'lulusan',
        'instansi_nama',
        'instansi_jabatan',
        'instansi_alamat',
        'instansi_k_propinsi',
        'instansi_k_kota',
        'instansi_kodepos',
        'k_pcp_paud',
        'pengalaman',
        'is_pembimbing',
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
    public function mPcpPaud()
    {
        return $this->belongsTo('App\Models\MPcpPaud', 'k_pcp_paud', 'k_pcp_paud');
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
    public function paudPengajarBerkases()
    {
        return $this->hasMany('App\Models\PaudPengajarBerkas', 'paud_pengajar_id', 'paud_pengajar_id');
    }

    public function isVervalKandidat()
    {
        return $this->k_verval_paud == MVervalPaud::KANDIDAT;
    }

    public function isVervalRevisi()
    {
        return $this->k_verval_paud == MVervalPaud::REVISI;
    }

    public function isVervalDiajukan()
    {
        return $this->k_verval_paud == MVervalPaud::DIAJUKAN;
    }
}
