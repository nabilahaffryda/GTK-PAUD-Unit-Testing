<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudPembimbingBerkas
 *
 * @property int $paud_pembimbing_berkas_id
 * @property int $paud_pembimbing_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_berkas_pembimbing_paud
 * @property null|string $nama
 * @property null|string $file
 * @property null|string $keterangan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $admin_id
 *
 * @property-read Akun $akun
 * @property-read MBerkasPembimbingPaud $mBerkasPembimbingPaud
 * @property-read PaudPembimbing $paudPembimbing
 *
 * @method static Builder|PaudPembimbingBerkas wherePaudPembimbingBerkasId($value)
 * @method static Builder|PaudPembimbingBerkas wherePaudPembimbingId($value)
 * @method static Builder|PaudPembimbingBerkas whereAkunId($value)
 * @method static Builder|PaudPembimbingBerkas whereTahun($value)
 * @method static Builder|PaudPembimbingBerkas whereAngkatan($value)
 * @method static Builder|PaudPembimbingBerkas whereKBerkasPembimbingPaud($value)
 * @method static Builder|PaudPembimbingBerkas whereNama($value)
 * @method static Builder|PaudPembimbingBerkas whereFile($value)
 * @method static Builder|PaudPembimbingBerkas whereKeterangan($value)
 * @method static Builder|PaudPembimbingBerkas whereCreatedAt($value)
 * @method static Builder|PaudPembimbingBerkas whereUpdatedAt($value)
 * @method static Builder|PaudPembimbingBerkas whereAdminId($value)
 */
class PaudPembimbingBerkas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_pembimbing_berkas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_pembimbing_berkas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_pembimbing_id'       => 'int',
        'akun_id'                  => 'string',
        'tahun'                    => 'int',
        'angkatan'                 => 'int',
        'k_berkas_pembimbing_paud' => 'int',
        'nama'                     => 'string',
        'file'                     => 'string',
        'keterangan'               => 'string',
        'created_at'               => 'datetime',
        'updated_at'               => 'datetime',
        'admin_id'                 => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_pembimbing_berkas_id',
        'paud_pembimbing_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_berkas_pembimbing_paud',
        'nama',
        'file',
        'keterangan',
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
    public function mBerkasPembimbingPaud()
    {
        return $this->belongsTo('App\Models\MBerkasPembimbingPaud', 'k_berkas_pembimbing_paud', 'k_berkas_pembimbing_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudPembimbing()
    {
        return $this->belongsTo('App\Models\PaudPembimbing', 'paud_pembimbing_id', 'paud_pengajar_id');
    }
}
