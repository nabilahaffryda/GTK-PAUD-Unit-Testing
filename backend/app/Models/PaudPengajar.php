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
 * @property null|string $instansi_lulus
 * @property null|int $is_pcp
 * @property null|int $pengalaman
 * @property null|int $k_kualifikasi
 * @property null|int $is_pembimbing
 * @property null|string $akun_id_verval
 * @property null|string $alasan
 * @property null|string $catatan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $admin_id
 *
 * @property-read Akun $akun
 * @property-read Collection|PaudPengajarBerkas[] $paudPengajarBerkases
 *
 * @method static Builder|PaudPengajar wherePaudPengajarId($value)
 * @method static Builder|PaudPengajar whereAkunId($value)
 * @method static Builder|PaudPengajar whereTahun($value)
 * @method static Builder|PaudPengajar whereAngkatan($value)
 * @method static Builder|PaudPengajar whereKVervalPaud($value)
 * @method static Builder|PaudPengajar whereWktAjuan($value)
 * @method static Builder|PaudPengajar whereWktVerval($value)
 * @method static Builder|PaudPengajar whereInstansiLulus($value)
 * @method static Builder|PaudPengajar whereIsPcp($value)
 * @method static Builder|PaudPengajar wherePengalaman($value)
 * @method static Builder|PaudPengajar whereKKualifikasi($value)
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
        'akun_id'        => 'string',
        'tahun'          => 'int',
        'angkatan'       => 'int',
        'k_verval_paud'  => 'int',
        'wkt_ajuan'      => 'datetime',
        'wkt_verval'     => 'datetime',
        'instansi_lulus' => 'string',
        'is_pcp'         => 'int',
        'pengalaman'     => 'int',
        'k_kualifikasi'  => 'int',
        'is_pembimbing'  => 'int',
        'akun_id_verval' => 'string',
        'alasan'         => 'string',
        'catatan'        => 'string',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
        'admin_id'       => 'string',
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
        'instansi_lulus',
        'is_pcp',
        'pengalaman',
        'k_kualifikasi',
        'is_pembimbing',
        'akun_id_verval',
        'alasan',
        'catatan',
        'admin_id',
    ];

    /**
     * @return BelongsTo|Builder|Akun
     */
    public function akun()
    {
        return $this->belongsTo('App\Models\Akun', 'akun_id', 'akun_id');
    }

    /**
     * @return HasMany|Builder|PaudPengajarBerkas
     */
    public function paudPengajarBerkases()
    {
        return $this->hasMany('App\Models\PaudPengajarBerkas', 'paud_pengajar_id', 'paud_pengajar_id');
    }
}
