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
 * @property int $k_berkas_paud
 * @property null|string $nama
 * @property null|string $file
 * @property null|string $diklat
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read Instansi $instansi
 * @property-read MBerkasPaud $mBerkasPaud
 * @property-read PaudInstansi $paudInstansi
 *
 * @method static Builder|PaudInstansiBerkas wherePaudInstansiBerkasId($value)
 * @method static Builder|PaudInstansiBerkas wherePaudInstansiId($value)
 * @method static Builder|PaudInstansiBerkas whereInstansiId($value)
 * @method static Builder|PaudInstansiBerkas whereTahun($value)
 * @method static Builder|PaudInstansiBerkas whereAngkatan($value)
 * @method static Builder|PaudInstansiBerkas whereKBerkasPaud($value)
 * @method static Builder|PaudInstansiBerkas whereNama($value)
 * @method static Builder|PaudInstansiBerkas whereFile($value)
 * @method static Builder|PaudInstansiBerkas whereDiklat($value)
 * @method static Builder|PaudInstansiBerkas whereCreatedAt($value)
 * @method static Builder|PaudInstansiBerkas whereUpdatedAt($value)
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
        'paud_instansi_id' => 'int',
        'instansi_id'      => 'int',
        'tahun'            => 'int',
        'angkatan'         => 'int',
        'k_berkas_paud'    => 'int',
        'nama'             => 'string',
        'file'             => 'string',
        'diklat'           => 'string',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
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
        'k_berkas_paud',
        'nama',
        'file',
        'diklat',
    ];

    /**
     * @return BelongsTo|Builder|Instansi
     */
    public function instansi()
    {
        return $this->belongsTo('App\Models\Instansi', 'instansi_id', 'instansi_id');
    }

    /**
     * @return BelongsTo|Builder|MBerkasPaud
     */
    public function mBerkasPaud()
    {
        return $this->belongsTo('App\Models\MBerkasPaud', 'k_berkas_paud', 'k_berkas_paud');
    }

    /**
     * @return BelongsTo|Builder|PaudInstansi
     */
    public function paudInstansi()
    {
        return $this->belongsTo('App\Models\PaudInstansi', 'paud_instansi_id', 'paud_instansi_id');
    }
}
