<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PaudPengajarBerkas
 *
 * @property int $paud_pengajar_berkas_id
 * @property int $paud_pengajar_id
 * @property null|string $akun_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_berkas_pengajar_paud
 * @property null|string $nama
 * @property null|string $file
 * @property null|string $keterangan
 * @property null|string $validasi
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property null|string $admin_id
 *
 * @property-read Akun $akun
 * @property-read MBerkasPengajarPaud $mBerkasPengajarPaud
 * @property-read PaudPengajar $paudPengajar
 *
 * @method static Builder|PaudPengajarBerkas wherePaudPengajarBerkasId($value)
 * @method static Builder|PaudPengajarBerkas wherePaudPengajarId($value)
 * @method static Builder|PaudPengajarBerkas whereAkunId($value)
 * @method static Builder|PaudPengajarBerkas whereTahun($value)
 * @method static Builder|PaudPengajarBerkas whereAngkatan($value)
 * @method static Builder|PaudPengajarBerkas whereKBerkasPengajarPaud($value)
 * @method static Builder|PaudPengajarBerkas whereNama($value)
 * @method static Builder|PaudPengajarBerkas whereFile($value)
 * @method static Builder|PaudPengajarBerkas whereKeterangan($value)
 * @method static Builder|PaudPengajarBerkas whereValidasi($value)
 * @method static Builder|PaudPengajarBerkas whereCreatedAt($value)
 * @method static Builder|PaudPengajarBerkas whereUpdatedAt($value)
 * @method static Builder|PaudPengajarBerkas whereAdminId($value)
 */
class PaudPengajarBerkas extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_pengajar_berkas';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_pengajar_berkas_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paud_pengajar_id'       => 'int',
        'akun_id'                => 'string',
        'tahun'                  => 'int',
        'angkatan'               => 'int',
        'k_berkas_pengajar_paud' => 'int',
        'nama'                   => 'string',
        'file'                   => 'string',
        'keterangan'             => 'string',
        'validasi'               => 'string',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
        'admin_id'               => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_pengajar_berkas_id',
        'paud_pengajar_id',
        'akun_id',
        'tahun',
        'angkatan',
        'k_berkas_pengajar_paud',
        'nama',
        'file',
        'keterangan',
        'validasi',
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
     * @return BelongsTo|Builder|MBerkasPengajarPaud
     */
    public function mBerkasPengajarPaud()
    {
        return $this->belongsTo('App\Models\MBerkasPengajarPaud', 'k_berkas_pengajar_paud', 'k_berkas_pengajar_paud');
    }

    /**
     * @return BelongsTo|Builder|PaudPengajar
     */
    public function paudPengajar()
    {
        return $this->belongsTo('App\Models\PaudPengajar', 'paud_pengajar_id', 'paud_pengajar_id');
    }
}
