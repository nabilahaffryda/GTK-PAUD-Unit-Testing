<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\PaudInstansi
 *
 * @property int $paud_instansi_id
 * @property int $instansi_id
 * @property null|int $tahun
 * @property null|int $angkatan
 * @property int $k_verval_paud
 * @property null|string $kodepos
 * @property null|string $nama_penanggung_jawab
 * @property null|string $telp_penanggung_jawab
 * @property null|string $nama_sekretaris
 * @property null|string $telp_sekretaris
 * @property null|string $nama_bendahara
 * @property null|string $telp_bendahara
 * @property null|string $diklat
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @property-read Instansi $instansi
 * @property-read MVervalPaud $mVervalPaud
 * @property-read Collection|PaudInstansiBerkas[] $paudInstansiBerkases
 *
 * @method static Builder|PaudInstansi wherePaudInstansiId($value)
 * @method static Builder|PaudInstansi whereInstansiId($value)
 * @method static Builder|PaudInstansi whereTahun($value)
 * @method static Builder|PaudInstansi whereAngkatan($value)
 * @method static Builder|PaudInstansi whereKVervalPaud($value)
 * @method static Builder|PaudInstansi whereKodepos($value)
 * @method static Builder|PaudInstansi whereNamaPenanggungJawab($value)
 * @method static Builder|PaudInstansi whereTelpPenanggungJawab($value)
 * @method static Builder|PaudInstansi whereNamaSekretaris($value)
 * @method static Builder|PaudInstansi whereTelpSekretaris($value)
 * @method static Builder|PaudInstansi whereNamaBendahara($value)
 * @method static Builder|PaudInstansi whereTelpBendahara($value)
 * @method static Builder|PaudInstansi whereDiklat($value)
 * @method static Builder|PaudInstansi whereCreatedAt($value)
 * @method static Builder|PaudInstansi whereUpdatedAt($value)
 */
class PaudInstansi extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paud_instansi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'paud_instansi_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'instansi_id'           => 'int',
        'tahun'                 => 'int',
        'angkatan'              => 'int',
        'k_verval_paud'         => 'int',
        'kodepos'               => 'string',
        'nama_penanggung_jawab' => 'string',
        'telp_penanggung_jawab' => 'string',
        'nama_sekretaris'       => 'string',
        'telp_sekretaris'       => 'string',
        'nama_bendahara'        => 'string',
        'telp_bendahara'        => 'string',
        'diklat'                => 'string',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'paud_instansi_id',
        'instansi_id',
        'tahun',
        'angkatan',
        'k_verval_paud',
        'kodepos',
        'nama_penanggung_jawab',
        'telp_penanggung_jawab',
        'nama_sekretaris',
        'telp_sekretaris',
        'nama_bendahara',
        'telp_bendahara',
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
     * @return BelongsTo|Builder|MVervalPaud
     */
    public function mVervalPaud()
    {
        return $this->belongsTo('App\Models\MVervalPaud', 'k_verval_paud', 'k_verval_paud');
    }

    /**
     * @return HasMany|Builder|PaudInstansiBerkas
     */
    public function paudInstansiBerkases()
    {
        return $this->hasMany('App\Models\PaudInstansiBerkas', 'paud_instansi_id', 'paud_instansi_id');
    }

    /**
     * @return BelongsTo|Builder|Instansi
     */
    public function paudInstansi()
    {
        return $this->belongsTo('App\Models\Instansi', 'paud_instansi_id', 'instansi_id');
    }
}
