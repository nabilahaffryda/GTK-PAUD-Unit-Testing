<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\MStatusEmail
 *
 * @property int $k_status_email
 * @property null|string $singkat
 * @property null|string $keterangan
 *
 * @method static Builder|MStatusEmail whereKStatusEmail($value)
 * @method static Builder|MStatusEmail whereSingkat($value)
 * @method static Builder|MStatusEmail whereKeterangan($value)
 */
class MStatusEmail extends Eloquent
{
    public const BELUM_AKTIVASI = 1;
    public const ANGGAP_AKTIVASI = 2;
    public const SUDAH_AKTIVASI = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_status_email';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_status_email';

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
        'k_status_email',
        'singkat',
        'keterangan',
    ];
}
