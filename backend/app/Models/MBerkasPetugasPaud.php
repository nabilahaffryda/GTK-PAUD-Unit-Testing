<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MBerkasPetugasPaud
 *
 * @property int $k_berkas_petugas_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|int $maks
 * @property null|string $validasi
 *
 * @property-read Collection|PaudPetugasBerkas[] $paudPetugasBerkases
 *
 * @method static Builder|MBerkasPetugasPaud whereKBerkasPetugasPaud($value)
 * @method static Builder|MBerkasPetugasPaud whereSingkat($value)
 * @method static Builder|MBerkasPetugasPaud whereKeterangan($value)
 * @method static Builder|MBerkasPetugasPaud whereMaks($value)
 * @method static Builder|MBerkasPetugasPaud whereValidasi($value)
 */
class MBerkasPetugasPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_berkas_petugas_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_berkas_petugas_paud';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'singkat'    => 'string',
        'keterangan' => 'string',
        'maks'       => 'int',
        'validasi'   => 'string',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'k_berkas_petugas_paud',
        'singkat',
        'keterangan',
        'maks',
        'validasi',
    ];

    /**
     * @return HasMany
     */
    public function paudPetugasBerkases()
    {
        return $this->hasMany('App\Models\PaudPetugasBerkas', 'k_berkas_petugas_paud', 'k_berkas_petugas_paud');
    }
}
