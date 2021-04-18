<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\MGroup
 *
 * @property int $k_group
 * @property null|string $singkat
 * @property null|string $keterangan
 * @property null|int $k_jenis_instansi
 *
 * @property-read MJenisInstansi $mJenisInstansi
 * @property-read Collection|AkunInstansi[] $akunInstansis
 * @property-read Collection|PaudAdmin[] $paudAdmins
 * @property-read Collection|PaudGroupAkses[] $paudGroupAkseses
 *
 * @method static Builder|MGroup whereKGroup($value)
 * @method static Builder|MGroup whereSingkat($value)
 * @method static Builder|MGroup whereKeterangan($value)
 * @method static Builder|MGroup whereKJenisInstansi($value)
 */
class MGroup extends Eloquent
{
    public const AI_PUSAT = 1;
    public const AI_P4TK = 2;
    public const AI_PROPINSI = 3;
    public const AI_KOTA = 4;
    public const OP_PUSAT = 5;
    public const OP_UPT = 6;
    public const OP_PROPINSI = 7;
    public const OP_KOTA = 8;
    public const KO_LMS = 10;
    public const ADM_LMS = 11;
    public const OP_LMS = 12;
    public const OP_TM = 13;
    public const OP_PB = 14;
    public const AI_DIKDASMEN = 15;
    public const OP_DIKDASMEN = 16;
    public const AI_TENDIK = 17;
    public const OP_TENDIK = 18;
    public const AI_LP2KS = 19;
    public const OP_LP2KS = 20;
    public const PENJAB_UPT = 21;
    public const FASILITATOR = 22;
    public const ADM_KG = 23;
    public const ADM_KG_PUSAT = 24;
    public const AI_LPMP = 25;
    public const OP_LPMP = 26;
    public const HELPDESK_PPGJ = 27;
    public const K13_PUSAT = 28;
    public const K13_PROPINSI = 29;
    public const K13_KOTA = 30;
    public const K13_P4TK = 31;
    public const K13_LPMP = 32;
    public const K13_DIKDASMEN = 33;
    public const K13_LP2KS = 34;
    public const TENDIK_PROPINSI = 35;
    public const TENDIK_KOTA = 36;
    public const OPK13_DIKDASMEN = 37;
    public const OP_MONEV_DIKDASMEN = 38;
    public const K13_DIKDAS_ACEH = 39;
    public const TENDIK_P4TK = 40;
    public const TENDIK_LPMP = 41;
    public const PKP_PUSAT = 42;
    public const PKP_DIKDASMEN = 43;
    public const PKP_P4TK = 44;
    public const PKP_PROPINSI = 45;
    public const PKP_KOTA = 46;
    public const AI_PAUD = 47;
    public const PKP_PAUD = 48;
    public const PBS_KOTA = 49;
    public const PBS_PROPINSI = 50;
    public const PBS_DIKMEN = 51;
    public const MERDEKA_PUSAT = 52;
    public const AI_LPTK = 53;
    public const OP_LPTK = 54;
    public const MERDEKA_P4TK = 55;
    public const TENDIK_DIKDAS = 56;
    public const TENDIK_DIKMEN = 57;
    public const TENDIK_PAUD = 58;
    public const EVAL_LPTK = 59;
    public const AI_SMERU = 60;
    public const KO_SMERU = 61;
    public const EVAL_SMERU = 62;
    public const FASILITATOR_PPG = 63;
    public const AI_ASESOR_GPR = 64;
    public const MST_ASESOR_GPR = 65;
    public const ASESOR_MENTOR_GPR = 66;
    public const VISIT_SMERU = 67;
    public const GPR_PUSAT = 68;
    public const VISIT_PUSAT = 69;
    public const PPG_PUSAT = 70;
    public const GPR_VERVAL_PUSAT = 71;
    public const ASESOR_FASIL_GPR = 72;
    public const ADM_LMS_LPTK = 73;
    public const ADM_LMS_PPG_PUSAT = 74;
    public const AI_VOKASI = 75;
    public const OP_VOKASI = 76;
    public const GPK_KOTA = 77;
    public const GPK_PROPINSI = 78;
    public const GPK_PUSAT = 79;
    public const GPR_VERVAL_CGP_PUSAT = 80;
    public const AP_LPD = 81;
    public const ADM_LPD = 82;
    public const ADM_LMS_LPD = 83;
    public const FASIL_LPD = 84;
    public const SPV_LPD = 85;
    public const OPR_SIM_LPD = 86;
    public const KO_LPTK = 87;
    public const ASESOR_GURU_GPR = 88;
    public const ADM_STUDI_PUSAT = 89;
    public const OPR_STUDI_PUSAT = 90;
    public const ADM_STUDI_KOTA = 91;
    public const OPR_STUDI_KOTA = 92;
    public const ADM_STUDI_PROPINSI = 93;
    public const OPR_STUDI_PROPINSI = 94;
    public const OP_PRAJAB_LPTK = 95;
    public const ADM_LMS_PRAJAB_LPTK = 96;
    public const KO_PRAJAB_LPTK = 97;
    public const NARASUMBER_GPK = 98;
    public const INSTRUKTUR_GPK = 99;
    public const ADM_LMS_GPK = 100;
    public const MST_ASESOR_FAMEN_GPR = 101;
    public const OP_GPR = 102;
    public const ADM_LMS_GPR = 103;
    public const INSTRUKTUR_GPR = 104;
    public const AP_PPG_DALJAB_PUSAT = 105;
    public const ADM_PPG_DALJAB_PUSAT = 106;
    public const PENGAWAS_PPG_DALJAB_PUSAT = 108;
    public const AP_PPG_PRAJAB_PUSAT = 109;
    public const ADM_PPG_PRAJAB_PUSAT = 110;
    public const ADM_LMS_PPG_PRAJAB_PUSAT = 111;
    public const PENGAWAS_PPG_PRAJAB_PUSAT = 112;
    public const AP_GPK_PUSAT = 113;
    public const ADM_KONTEN_GPK_PUSAT = 114;
    public const AP_PPGTKMP = 115;
    public const PENGAWAS_GPK_PUSAT = 116;
    public const NARSUM_DEMO_LPD = 117;
    public const PESERTA_DEMO_LPD = 118;
    public const ADMIN_DEMO_LPD = 119;
    public const PEMANTAU_GPR_PUSAT = 120;
    public const ADM_PPGTKMP_PUSAT = 121;
    public const ADM_LMS_PPGTKMP = 122;
    public const EVALUATOR_PPGTKMP = 123;
    public const NARASUMBER_PRAJAB_PUSAT = 124;
    public const SPV_DEMO_LPD = 125;
    public const MENTOR_DEMO_LPD = 126;
    public const KORMIN_DEMO_LPD = 127;
    public const SUPPORT_PPGTKMP = 128;
    public const AI_LPD = 129;
    public const REVIEWER_PPGTKMP = 130;
    public const AP_GPR_P4TK = 131;
    public const ADM_KELAS_LMS_CGP = 132;
    public const OPR_SIM_CGP = 133;
    public const INSTRUKTUR_CGP = 134;
    public const AP_STUDI_UNIV = 135;
    public const OPR_STUDI_UNIV = 136;
    public const HELPDESK_PUSAT = 137;
    public const MASTER_TRAINER_PPGTKMP = 138;
    public const MST_ASESOR_FASIL_GPR = 139;
    public const AP_PSP_PUSAT = 140;
    public const ADM_PSP_PUSAT = 141;
    public const TIM_VERVAL_KS_PSP = 142;
    public const TIM_VERVAL_PA_PSP = 143;
    public const PEMANTAU_PSP_PUSAT = 144;
    public const PEMANTAU_ASESOR_KS_PSP = 145;
    public const PEMANTAU_ASESOR_PA_PSP = 146;
    public const ASESOR_KS_PSP = 147;
    public const ASESOR_PA_PSP = 148;
    public const MERDEKA_DIKDAS = 149;
    public const MERDEKA_DIKMEN = 150;
    public const MERDEKA_PAUD = 151;
    public const AI_P3GTK = 152;
    public const ADM_PPGDJ_P3GTK = 153;
    public const ADM_PPGDJ_LPMP = 154;
    public const ADM_PPGDJ_PROPINSI = 155;
    public const ADM_PPGDJ_KOTA = 156;
    public const AI_BPPAUD = 157;
    public const AP_PSP_BPPAUD = 158;
    public const ADM_PSP_BPPAUD = 159;
    public const AP_PPG_DALJAB_LPMP = 160;
    public const AP_PPG_PRAJAB_LPMP = 161;
    public const AP_PPG_DALJAB_PROPINSI = 162;
    public const AP_PPG_PRAJAB_PROPINSI = 163;
    public const AP_PPG_DALJAB_KOTA = 164;
    public const AP_PPG_PRAJAB_KOTA = 165;
    public const AP_PPG_DALJAB_P3GTK = 166;
    public const AP_PPG_PRAJAB_P3GTK = 167;
    public const EVAL_MERDEKA_PUSAT = 168;
    public const PL_MERDEKA = 169;
    public const AP_GTK_PAUD_DIKLAT_PAUD = 170;
    public const AP_LPD_DIKLAT_PAUD = 171;
    public const OP_LPD_DIKLAT_PAUD = 172;
    public const PENGAJAR_BIMTEK_DIKLAT_PAUD = 173;
    public const PENGAJAR_DIKLAT_PAUD = 174;
    public const PENGAJAR_TAMBAHAN_DIKLAT_PAUD = 175;
    public const PEMBIMBING_PRAKTIK_DIKLAT_PAUD = 176;
    public const ADM_KELAS_DIKLAT_PAUD = 177;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_group';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'k_group';

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
        'singkat'          => 'string',
        'keterangan'       => 'string',
        'k_jenis_instansi' => 'int',
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
        'k_group',
        'singkat',
        'keterangan',
        'k_jenis_instansi',
    ];

    /**
     * @return HasMany
     */
    public function akunInstansis()
    {
        return $this->hasMany('App\Models\AkunInstansi', 'k_group', 'k_group');
    }

    /**
     * @return BelongsTo
     */
    public function mJenisInstansi()
    {
        return $this->belongsTo('App\Models\MJenisInstansi', 'k_jenis_instansi', 'k_jenis_instansi');
    }

    /**
     * @return HasMany
     */
    public function paudAdmins()
    {
        return $this->hasMany('App\Models\PaudAdmin', 'k_group', 'k_group');
    }

    /**
     * @return HasMany
     */
    public function paudGroupAkseses()
    {
        return $this->hasMany('App\Models\PaudGroupAkses', 'k_group', 'k_group');
    }
}
