<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudAdmin
 *
 * @property int $paud_admin_id
 * @property null|string $akun_id
 * @property null|int $k_group
 * @property null|int $instansi_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $is_aktif
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property null|string $admin_id
 *
 * @property-read Akun $akun
 * @property-read Instansi $instansi
 * @property-read MGroup $mGroup
 *
 * @method static Builder|PaudAdmin wherePaudAdminId($value)
 * @method static Builder|PaudAdmin whereAkunId($value)
 * @method static Builder|PaudAdmin whereKGroup($value)
 * @method static Builder|PaudAdmin whereInstansiId($value)
 * @method static Builder|PaudAdmin whereTahun($value)
 * @method static Builder|PaudAdmin whereAngkatan($value)
 * @method static Builder|PaudAdmin whereIsAktif($value)
 * @method static Builder|PaudAdmin whereCreatedAt($value)
 * @method static Builder|PaudAdmin whereUpdatedAt($value)
 * @method static Builder|PaudAdmin whereAdminId($value)
 */
class PaudAdmin extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_admin';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_admin_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'akun_id'     => 'string',
        'k_group'     => 'int',
        'instansi_id' => 'int',
        'tahun'       => 'int',
        'angkatan'    => 'int',
        'is_aktif'    => 'int',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'admin_id'    => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_admin_id',
        'akun_id',
        'k_group',
        'instansi_id',
        'tahun',
        'angkatan',
        'is_aktif',
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
    public function instansi()
    {
        return $this->belongsTo('App\Models\Instansi', 'instansi_id', 'instansi_id');
    }

    /**
     * @return BelongsTo
     */
    public function mGroup()
    {
        return $this->belongsTo('App\Models\MGroup', 'k_group', 'k_group');
    }
}
