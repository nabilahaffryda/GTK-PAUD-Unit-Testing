<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\MKonfirmasiPaud
 *
 * @property int $k_konfirmasi_paud
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @method static Builder|MKonfirmasiPaud whereKKonfirmasiPaud($value)
 * @method static Builder|MKonfirmasiPaud whereSingkat($value)
 * @method static Builder|MKonfirmasiPaud whereKeterangan($value)
 */
class MKonfirmasiPaud extends Eloquent
{
    public const KANDIDAT         = 1;
    public const BELUM_KONFIRMASI = 2;
    public const BERSEDIA         = 3;
    public const TIDAK_BERSEDIA   = 4;
    public const DIHAPUS          = 5;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_konfirmasi_paud';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_konfirmasi_paud';

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
        'k_konfirmasi_paud',
        'singkat',
        'keterangan',
    ];
}
