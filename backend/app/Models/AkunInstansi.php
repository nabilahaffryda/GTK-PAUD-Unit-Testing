<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\AkunInstansi
 *
 * @property int $akun_instansi_id
 * @property string $akun_id
 * @property null|int $k_group
 * @property null|int $instansi_id
 * @property null|string $token
 * @property string $is_aktif
 * @property null|string $admin_id
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read Akun $akun
 * @property-read Instansi $instansi
 * @property-read MGroup $mGroup
 *
 * @method static Builder|AkunInstansi whereAkunInstansiId($value)
 * @method static Builder|AkunInstansi whereAkunId($value)
 * @method static Builder|AkunInstansi whereKGroup($value)
 * @method static Builder|AkunInstansi whereInstansiId($value)
 * @method static Builder|AkunInstansi whereToken($value)
 * @method static Builder|AkunInstansi whereIsAktif($value)
 * @method static Builder|AkunInstansi whereAdminId($value)
 * @method static Builder|AkunInstansi whereCreatedAt($value)
 * @method static Builder|AkunInstansi whereUpdatedAt($value)
 */
class AkunInstansi extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'akun_instansi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'akun_instansi_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'akun_id'     => 'string',
        'k_group'     => 'int',
        'instansi_id' => 'int',
        'token'       => 'string',
        'is_aktif'    => 'string',
        'admin_id'    => 'string',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'akun_instansi_id',
        'akun_id',
        'k_group',
        'instansi_id',
        'token',
        'is_aktif',
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
     * @return BelongsTo|Builder|Instansi
     */
    public function instansi()
    {
        return $this->belongsTo('App\Models\Instansi', 'instansi_id', 'instansi_id');
    }

    /**
     * @return BelongsTo|Builder|MGroup
     */
    public function mGroup()
    {
        return $this->belongsTo('App\Models\MGroup', 'k_group', 'k_group');
    }
}
