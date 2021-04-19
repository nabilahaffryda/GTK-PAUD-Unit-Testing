<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudInstansiBerkas
 *
 * @property int $paud_instansi_berkas_id
 * @property int $paud_instansi_id
 * @property null|int $instansi_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_berkas_lpd_paud
 * @property null|string $nama
 * @property null|string $file
 * @property null|string $keterangan
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $akun_id
 *
 * @property-read null|string $url
 *
 * @property-read Instansi $instansi
 * @property-read MBerkasLpdPaud $mBerkasLpdPaud
 * @property-read PaudInstansi $paudInstansi
 *
 * @method static Builder|PaudInstansiBerkas wherePaudInstansiBerkasId($value)
 * @method static Builder|PaudInstansiBerkas wherePaudInstansiId($value)
 * @method static Builder|PaudInstansiBerkas whereInstansiId($value)
 * @method static Builder|PaudInstansiBerkas whereTahun($value)
 * @method static Builder|PaudInstansiBerkas whereAngkatan($value)
 * @method static Builder|PaudInstansiBerkas whereKBerkasLpdPaud($value)
 * @method static Builder|PaudInstansiBerkas whereNama($value)
 * @method static Builder|PaudInstansiBerkas whereFile($value)
 * @method static Builder|PaudInstansiBerkas whereKeterangan($value)
 * @method static Builder|PaudInstansiBerkas whereCreatedAt($value)
 * @method static Builder|PaudInstansiBerkas whereUpdatedAt($value)
 * @method static Builder|PaudInstansiBerkas whereAkunId($value)
 */
class PaudInstansiBerkas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_instansi_berkas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_instansi_berkas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_instansi_id'  => 'int',
        'instansi_id'       => 'int',
        'tahun'             => 'int',
        'angkatan'          => 'int',
        'k_berkas_lpd_paud' => 'int',
        'nama'              => 'string',
        'file'              => 'string',
        'keterangan'        => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'akun_id'           => 'string',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_instansi_berkas_id',
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_berkas_lpd_paud',
        'nama',
        'file',
        'keterangan',
        'akun_id',
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
    public function mBerkasLpdPaud()
    {
        return $this->belongsTo('App\Models\MBerkasLpdPaud', 'k_berkas_lpd_paud', 'k_berkas_lpd_paud');
    }

    /**
     * @return BelongsTo
     */
    public function paudInstansi()
    {
        return $this->belongsTo('App\Models\PaudInstansi', 'paud_instansi_id', 'paud_instansi_id');
    }

    public function getUrlAttribute()
    {
        return $this->file ? sprintf("%s/%s", config('filesystems.disks.lpd-berkas.url'), $this->file) : null;
    }
}
