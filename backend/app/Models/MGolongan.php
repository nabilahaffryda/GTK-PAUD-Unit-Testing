<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MGolongan
 *
 * @property int $k_golongan
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @property-read Collection|Akun[] $akuns
 *
 * @method static Builder|MGolongan whereKGolongan($value)
 * @method static Builder|MGolongan whereSingkat($value)
 * @method static Builder|MGolongan whereKeterangan($value)
 */
class MGolongan extends Eloquent
{
    public const CPNS = -2;
    public const PNS = -1;
    public const IA = 1;
    public const IB = 2;
    public const IC = 3;
    public const ID = 4;
    public const IIA = 5;
    public const IIB = 6;
    public const IIC = 7;
    public const IID = 8;
    public const IIIA = 9;
    public const IIIB = 10;
    public const IIIC = 11;
    public const IIID = 12;
    public const IVA = 13;
    public const IVB = 14;
    public const IVC = 15;
    public const IVD = 16;
    public const IVE = 17;
    public const TIDAK_ADA = 99;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_golongan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_golongan';

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
        'k_golongan',
        'singkat',
        'keterangan',
    ];

    /**
     * @return HasMany|Builder|Akun
     */
    public function akuns()
    {
        return $this->hasMany('App\Models\Akun', 'k_golongan', 'k_golongan');
    }
}
