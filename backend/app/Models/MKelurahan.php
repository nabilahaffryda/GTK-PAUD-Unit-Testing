<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//region ### Additional namespace #
//endregion

/**
 * App\Models\MKelurahan
 *
 * @property integer                       $k_kelurahan
 * @property integer                       $k_kecamatan
 * @property null|string                   $singkat
 * @property string                        $keterangan
 * @property null|integer                  $kode_dagri
 *
 * @property-read Collection|PaudKelas[]   $paudKelases
 * @property-read Collection|PaudPetugas[] $paudPetugases
 * @property-read MKecamatan               $mKecamatan
 *
 * @method static Builder|MKelurahan whereKKelurahan($value)
 * @method static Builder|MKelurahan whereKKecamatan($value)
 * @method static Builder|MKelurahan whereSingkat($value)
 * @method static Builder|MKelurahan whereKeterangan($value)
 * @method static Builder|MKelurahan whereKodeDagri($value)
 *
 * @method static Builder|MKelurahan query()
 *
 * @method static Collection|MKelurahan[]     all($columns = ['*'])
 * @method static MKelurahan|null             find($id, $columns = ['*'])
 * @method static Collection|MKelurahan[]     findMany($ids, $columns = ['*'])
 * @method static MKelurahan                  findOrNew($id, $columns = ['*'])
 * @method static MKelurahan                  findOrFail($id, $columns = ['*'])
 * @method static MKelurahan|null             first($columns = ['*'])
 * @method static MKelurahan                  firstOrFail($columns = ['*'])
 * @method static MKelurahan                  firstOrNew($attributes, $values = array())
 * @method static MKelurahan                  firstOrCreate($attributes, $values = ['*'])
 * @method static MKelurahan                  updateOrCreate($attributes, $values = ['*'])
 * @method static Collection|MKelurahan[]     get($columns = ['*'])
 */
class MKelurahan extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_kelurahan';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_kelurahan';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'k_kelurahan',
        'k_kecamatan',
        'singkat',
        'keterangan',
        'kode_dagri',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'k_kelurahan' => 'integer',
        'k_kecamatan' => 'integer',
        'singkat'     => 'string',
        'keterangan'  => 'string',
        'kode_dagri'  => 'integer',
    ];

    /**
     * @return HasMany|Builder|PaudKelas
     */
    public function paudKelases()
    {
        return $this->hasMany('App\Models\PaudKelas', 'k_kelurahan', 'k_kelurahan');
    }

    /**
     * @return HasMany|Builder|PaudPetugas
     */
    public function paudPetugases()
    {
        return $this->hasMany('App\Models\PaudPetugas', 'k_kelurahan', 'k_kelurahan');
    }

    /**
     * @return BelongsTo|Builder|MKecamatan
     */
    public function mKecamatan()
    {
        return $this->belongsTo('App\Models\MKecamatan', 'k_kecamatan', 'k_kecamatan');
    }

    //region ### User defined function #
    //endregion
}
