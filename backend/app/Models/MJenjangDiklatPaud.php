<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
/**
 * App\Models\MJenjangDiklatPaud
 *
 * @property int $k_jenjang_diklat_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|PaudDiklatLuring[] $paudDiklatLurings
 * @property-read Collection|PaudPesertaNonptk[] $paudPesertaNonptks
 *
 * @method static Builder|MJenjangDiklatPaud whereKJenjangDiklatPaud($value)
 * @method static Builder|MJenjangDiklatPaud whereSingkat($value)
 * @method static Builder|MJenjangDiklatPaud whereKeterangan($value)
 */
class MJenjangDiklatPaud extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_jenjang_diklat_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_jenjang_diklat_paud';

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
        'singkat' => 'string',
        'keterangan' => 'string',
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
        'k_jenjang_diklat_paud',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany
     */
    public function paudDiklatLurings()
    {
        return $this->hasMany('App\Models\PaudDiklatLuring', 'k_jenjang_diklat_paud', 'k_jenjang_diklat_paud');
    }

    /**
     * @return HasMany
     */
    public function paudPesertaNonptks()
    {
        return $this->hasMany('App\Models\PaudPesertaNonptk', 'k_jenjang_diklat_paud', 'k_jenjang_diklat_paud');
    }
}
