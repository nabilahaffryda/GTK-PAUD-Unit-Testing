<?php

namespace App\Services;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\AkunInstansi;
use App\Models\Instansi;
use App\Models\MGroup;
use App\Models\Ptk;
use App\Remotes\Paspor\User;
use Arr;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Query;
use Illuminate\Support;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Log;

class AkunService
{
    protected static $instansiGroup = [
        MGroup::AP_GTK_PAUD_DIKLAT_PAUD        => [14],
        MGroup::ADM_GTK_PAUD_DIKLAT_PAUD       => [14],
        MGroup::AP_LPD_DIKLAT_PAUD             => [23],
        MGroup::OP_LPD_DIKLAT_PAUD             => [23],
        MGroup::PENGAJAR_BIMTEK_DIKLAT_PAUD    => [14],
        MGroup::PENGAJAR_DIKLAT_PAUD           => [14],
        MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD  => [23],
        MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD => [14, 23],
        MGroup::ADM_KELAS_DIKLAT_PAUD          => [23],
    ];

    public function kGroups()
    {
        return array_keys(static::$instansiGroup);
    }

    public static function childGroup($kGroup)
    {
        $groups = [
            MGroup::AP_GTK_PAUD_DIKLAT_PAUD  => [
                MGroup::ADM_GTK_PAUD_DIKLAT_PAUD,
            ],
            MGroup::ADM_GTK_PAUD_DIKLAT_PAUD => [
                MGroup::AP_LPD_DIKLAT_PAUD,
                MGroup::PENGAJAR_BIMTEK_DIKLAT_PAUD,
                MGroup::PENGAJAR_DIKLAT_PAUD,
                MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD,
            ],
            MGroup::AP_LPD_DIKLAT_PAUD       => [
                MGroup::OP_LPD_DIKLAT_PAUD,
            ],
            MGroup::OP_LPD_DIKLAT_PAUD       => [
                MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD,
                MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD,
                MGroup::ADM_KELAS_DIKLAT_PAUD,
            ],
        ];

        return $groups[$kGroup] ?? [];
    }

    /**
     * @param Akun $akun
     * @param Instansi $instansi
     *
     * @return MGroup[]|Eloquent\Collection
     */
    public static function childGroups(Akun $akun, Instansi $instansi)
    {
        $akunInstansis = AkunInstansi::whereAkunId($akun->akun_id)
            ->whereIsAktif('1')
            ->whereInstansiId($instansi->instansi_id)
            ->get();

        $kGroups = [];
        foreach ($akunInstansis as $akunInstansi) {
            $kGroups = array_merge($kGroups, static::childGroup($akunInstansi->k_group));
        }

        $kGroups = array_unique($kGroups);

        $groupInstansis = [];
        foreach ($kGroups as $kGroup) {
            if (in_array($instansi->k_jenis_instansi, static::$instansiGroup[$kGroup] ?? [])) {
                $groupInstansis[] = $kGroup;
            }
        }

        return MGroup::query()
            ->whereIn('k_group', $groupInstansis)
            ->get();
    }

    /**
     * @param Akun $akun
     *
     * @return AkunInstansi|null
     */
    public function akunInstansi(Akun $akun)
    {
        return $akun
            ->akunInstansis()
            ->where('is_aktif', '1')
            ->whereIn('k_group', $this->kGroups())
            ->first();
    }

    /**
     * @param Akun $akun
     * @param array $params
     *
     * @return Eloquent\Builder
     */
    public function queryInstansi(Akun $akun, $params)
    {
        $query = Instansi::query()
            ->select(['instansi_id', 'nama'])
            ->whereExists(function (Query\Builder $query) use ($akun) {
                $query->selectRaw(1)
                    ->from('akun_instansi')
                    ->where('akun_instansi.akun_id', '=', $akun->akun_id)
                    ->where('akun_instansi.is_aktif', '=', '1')
                    ->whereIn('akun_instansi.k_group', $this->kGroups())
                    ->whereColumn('instansi.instansi_id', 'akun_instansi.instansi_id');
            });

        if ($keyword = Arr::get($params, 'filter.keyword')) {
            $query->where('nama', 'like', "%$keyword%");
        }

        return $query;
    }

    /**
     * @param Instansi $instansi
     *
     * @return AkunInstansi[]|Eloquent\Collection
     */
    public function akunInstansis(Instansi $instansi)
    {
        static $akunInstansis = [];
        if (!isset($akunInstansis[$instansi->instansi_id])) {
            $akunInstansis[$instansi->instansi_id] = akun()
                ->akunInstansis()
                ->where('is_aktif', '1')
                ->whereIn('k_group', $this->kGroups())
                ->whereInstansiId($instansi->instansi_id)
                ->get();
        }

        return $akunInstansis[$instansi->instansi_id];
    }

    /**
     * @param Eloquent\Collection $akunInstansi
     *
     * @return Support\Collection
     */
    public function getGroups(Eloquent\Collection $akunInstansi)
    {
        return $akunInstansi
            ->load('mGroup')
            ->pluck('mGroup.keterangan', 'k_group');
    }

    /**
     * @param Instansi $instansi
     * @param array $kGroups
     *
     * @return bool
     */
    public function isGroup(Instansi $instansi, $kGroups)
    {
        $akunInstansi = $this->akunInstansis($instansi);
        if ($akunInstansi->whereIn('k_group', (array)$kGroups)->count()) {
            return true;
        }

        return false;
    }

    /**
     * @param Akun $akun
     * @param Instansi $instansi
     * @param array $kGroups
     *
     * @return bool
     */
    public function hasGroup(Akun $akun, Instansi $instansi, $kGroups)
    {
        $akunInstansi = $akun->akunInstansis;
        if ($akunInstansi->whereIn('k_group', (array)$kGroups)->count()) {
            return true;
        }

        return false;
    }

    /**
     * @param Akun $akun
     * @param Instansi $instansi
     * @param array $kGroups
     *
     * @return bool
     */
    public function hasOtherGroup(Akun $akun, Instansi $instansi, $kGroups)
    {
        $akunInstansi = $akun->akunInstansis;
        if ($akunInstansi->whereNotIn('instansi_id', [$instansi->instansi_id])->count()) {
            return true;
        }
        if ($akunInstansi->whereNotIn('k_group', (array)$kGroups)->count()) {
            return true;
        }

        return false;
    }

    /**
     * @param Eloquent\Collection $akunInstansi
     *
     * @return string|bool|integer
     */
    public function isAktivasi(Eloquent\Collection $akunInstansi)
    {
        $aktivasi = $akunInstansi->filter(function ($item) {
            return $item->token;
        });

        if ($aktivasi->isEmpty()) {
            return false;
        }

        return $aktivasi->first()->k_group;
    }

    /**
     * @param integer $pasporId
     * @param bool $force
     *
     * @return Akun|false
     * @throws GuzzleException
     */
    public function resetPaspor($pasporId, $force = false)
    {
        $akun = Akun::findOrFail($pasporId);
        if (!$akun->passwd || $force) {
            $akun->passwd = $this->passwd();
        }

        if ($akun->save() || $force) {
            $paspor = new User();
            if ($paspor->password($pasporId, $akun->passwd)) {
                return $akun->makeVisible(['passwd']);
            }
        }

        return false;
    }

    /**
     * @param Ptk $ptk
     * @param Akun $admin
     *
     * @return Ptk
     * @throws FlowException|GuzzleException
     */
    public function resetPasporPtk(Ptk $ptk, Akun $admin)
    {
        $email      = !empty($ptk->email) ? $ptk->email : sprintf('%s@%s', $ptk->ptk_id, config('app.email_domain'));
        $passwd     = $this->passwd();
        $paspor     = new User();
        $userPaspor = $paspor->getUserByEmail($email);

        Log::debug($userPaspor ?? "");
        if ($ptk->paspor_id && (!$userPaspor || ($userPaspor['userid'] != $ptk->paspor_id))) {
            $ptk->paspor_id = null;
        }

        if (!$ptk->paspor_id && !$userPaspor) {
            $users = [
                [
                    'nama'     => $ptk->nama,
                    'passwd'   => $passwd,
                    'email'    => $email,
                    'is_aktif' => '1',
                    'is_email' => '1',
                    'admin_id' => $admin->paspor_id,
                ],
            ];

            $paspor->add($users, [config('services.paspor.layanan_id')], $admin->paspor_id);
            $user = $paspor->getUserByEmail($email);

            $ptk->paspor_id = $user['userid'];
            $ptk->passwd    = $passwd;

        } else {
            if (!$ptk->passwd) {
                $ptk->passwd = $passwd;
            }

            if ($userPaspor && !$ptk->paspor_id) {
                $ptk->paspor_id = $userPaspor['userid'];
            }

            $paspor->password($ptk->paspor_id, $ptk->passwd, '1');
        }

        $ptk->email = $email;
        if (!$ptk->save()) {
            throw new FlowException('Penyimpanan data PTK gagal');
        }

        return $ptk;
    }

    /**
     * @param integer $len
     *
     * @return string
     */
    public function passwd($len = 5)
    {
        if (config('app.env') != 'production') {
            return '12345';
        }

        return strtoupper(Str::random($len));
    }

    /**
     * @param Akun $akun
     * @param array $data
     * @return Akun
     * @throws SaveException
     */
    public function update(Akun $akun, $data = [])
    {
        $akun->fill($data);
        if (!$akun->save()) {
            throw new SaveException("Penyimpanan Data Akun tidak Berhasil");
        }

        return $akun;
    }

    /**
     * @param Akun $akun
     * @param $foto
     * @param $ext
     * @return string
     * @throws FlowException
     */
    public function uploadFoto(Akun $akun, $foto, $ext)
    {
        $ftpPath   = config('filesystems.disks.akun-foto.path');
        $timestamp = date('ymdhis');
        $filename  = "{$akun->k_kota}/{$akun->k_propinsi}/{$akun->akun_id}-{$timestamp}.$ext";

        $path = sprintf("%s/%s", $ftpPath, $filename);
        if (!Storage::disk('akun-foto')->put($path, $foto)) {
            throw new FlowException("Unggah Foto Akun tidak berhasil");
        }

        return $filename;
    }

    /**
     * @param string $filename
     * @return boolean
     */
    public function deleteFoto($filename)
    {
        $ftpPath = config('filesystems.disks.akun-foto.path');

        $path = sprintf("%s/%s", $ftpPath, $filename);
        return Storage::disk('akun-foto')->delete($path);
    }

    /**
     * @throws AuthorizationException
     */
    public function validateInstansi(int $instansiId): Instansi
    {
        /** @var AkunInstansi $akunInstansi */
        $akunInstansi = akun()
            ?->akunInstansis()
            ->where('is_aktif', 1)
            ->where('instansi_id', $instansiId)
            ->first();

        if (!$akunInstansi) {
            throw new AuthorizationException("Instansi {$instansiId} tidak dikenali");
        }

        app()->instance('INSTANSI', $akunInstansi->instansi);
        return $akunInstansi->instansi;
    }

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\AuthorizationException
     */
    public function validateInstansiGraphQL(int $instansiId): Instansi
    {
        try {
            return $this->validateInstansi($instansiId);
        } catch (AuthorizationException $e) {
            throw new \Nuwave\Lighthouse\Exceptions\AuthorizationException($e->getMessage());
        }
    }
}
