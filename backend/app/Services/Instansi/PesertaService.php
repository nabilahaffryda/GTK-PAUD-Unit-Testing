<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\MKota;
use App\Models\PaudKelasPeserta;
use App\Models\PaudKelasPesertaLuring;
use App\Models\Ptk;
use App\Remotes\Paspor\User;
use App\Remotes\SimpatikaRemote;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;

class PesertaService
{
    public function queryKandidat(int $kKota, int $kJenjang = null, int $kSumber = null, int $kelasid = null, int $kelasLuringId = null)
    {
        return Ptk::query()
            ->select('ptk.*')
            ->distinct()
            ->leftJoin('ptk_sekolah', 'ptk_sekolah.ptk_id', '=', 'ptk.ptk_id')
            ->leftJoin('sekolah', 'sekolah.sekolah_id', '=', 'ptk_sekolah.sekolah_id')
            ->leftJoin('paud_kelas_peserta', function (JoinClause $query) {
                $query->on('paud_kelas_peserta.ptk_id', '=', 'ptk.ptk_id')
                    ->where('paud_kelas_peserta.tahun', '=', (int)config('paud.tahun'))
                    ->where('paud_kelas_peserta.angkatan', '=', (int)config('paud.angkatan'));
            })
            ->leftJoin('paud_kelas_peserta_luring', function (JoinClause $query) {
                $query->on('paud_kelas_peserta_luring.ptk_id', '=', 'ptk.ptk_id')
                    ->where('paud_kelas_peserta_luring.tahun', '=', (int)config('paud.tahun'))
                    ->where('paud_kelas_peserta_luring.angkatan', '=', (int)config('paud.angkatan'));
            })
            ->when($kSumber != 9, function (Builder $query) use ($kSumber) {
                $query->whereNotNull('dapodik_ptk_id');
            })
            ->when($kJenjang !== null, function (Builder $query) use ($kJenjang) {
                $query->where('sekolah.k_jenjang', '=', (int)$kJenjang);
            })
            ->when($kSumber !== null, function (Builder $query) use ($kSumber) {
                $query->where('k_sumber', '=', $kSumber);
            })
            ->where(function (Builder $query) use ($kKota) {
                $query->orWhere([
                    'ptk.k_kota'     => $kKota,
                    'sekolah.k_kota' => $kKota,
                ]);
            })
            ->where(function ($query) use ($kelasid) {
                $query->orWhereNull('paud_kelas_peserta.paud_kelas_peserta_id')
                    ->when($kelasid, function ($query, $value) {
                        $query->orWhere('paud_kelas_peserta.paud_kelas_id', '=', $value);
                    });
            })
            ->where(function ($query) use ($kelasLuringId) {
                $query->orWhereNull('paud_kelas_peserta_luring.paud_kelas_peserta_luring_id')
                    ->when($kelasLuringId, function ($query, $value) {
                        $query->orWhere('paud_kelas_peserta_luring.paud_kelas_luring_id', '=', $value);
                    });
            });
    }

    /**
     * @throws FlowException
     */
    public function kandidatSimpatika(MKota $mKota, array $params)
    {
        try {
            $result = app(SimpatikaRemote::class)
                ->searchGuruRA(
                    $mKota->k_kota_simpatika,
                    $params['keyword'] ?? '',
                    $params['page'] ?? 1,
                    $params['count'] ?? 10,
                );
        } catch (GuzzleException $e) {
            throw new FlowException('Gagal membaca data dari sistem SIMPATIKA, silakan dicoba beberapa saat lagi', previous: $e);
        }

        $data   = collect($result['data'] ?? []);
        $ptkIds = $data->pluck('ptk_id')->unique();

        $pesertaDarings = PaudKelasPeserta::query()
            ->where([
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ])
            ->whereIn('ptk_id', $ptkIds)
            ->get()
            ->keyBy('ptk_id');

        $pesertaLurings = PaudKelasPesertaLuring::query()
            ->whereIn('ptk_id', $ptkIds)
            ->get()
            ->keyBy('ptk_id');


        foreach ($result['data'] ?? [] as $index => $data) {
            $ptkId = $data['ptk_id'] ?? null;

            $result['data'][$index]['is_baru'] = !($ptkId && ($pesertaDarings->has($ptkId) || $pesertaLurings->has($ptkId)));

            unset($result['data'][$index]['instansi']);
            unset($result['data'][$index]['ptk_profils']);
        }

        return $result;
    }

    /**
     * @throws FlowException|GuzzleException
     */
    public function createSimpatika(MKota $mKota, array $ptkIds)
    {
        $ptks = Ptk::query()
            ->whereIn('ptk_id', $ptkIds)
            ->get()
            ->keyBy('ptk_id');

        $newPtkIds = array_diff($ptkIds, $ptks->keys()->all());
        if (!$newPtkIds) {
            return;
        }

        try {
            $ptks = app(SimpatikaRemote::class)->fetchGuruRA($newPtkIds);
        } catch (GuzzleException $e) {
            throw new FlowException('Gagal membaca data dari sistem SIMPATIKA, silakan dicoba beberapa saat lagi', previous: $e);
        }

        // simpan ke paspor
        $kKotaSimpatikas = [];
        $ptkIds          = [];
        $newUsers        = [];
        foreach ($ptks as $ptk) {
            $email = $ptk['email'];

            $ptkIds[$email]   = $ptk['ptk_id'];
            $newUsers[$email] = [
                'userid'   => $ptk['paspor_id'],
                'nama'     => $ptk['nama'],
                'passwd'   => null,
                'email'    => $email,
                'is_aktif' => '1',
                'is_email' => '1',
                'admin_id' => akun()?->paspor_id,
            ];

            if (isset($ptk['ptk_profils'][0]['k_kota'])) {
                $kKotaSimpatikas[] = $ptk['ptk_profils'][0]['k_kota'];
            }

            if (isset($ptk['instansi']['k_kota'])) {
                $kKotaSimpatikas[] = $ptk['instansi']['k_kota'];
            }
        }

        // cek user yang telah terdaftar dipaspor
        $sosialUsers = [];
        $pasporUsers = [];
        $response    = app(User::class)->listUserByEmails(array_keys($newUsers));
        foreach ($response as $item) {
            $pasporId = $item['userid'];
            $email    = $item['email'];

            $pasporUsers[$email] = $item;

            $sosialUsers[$pasporId] = [
                'sosialid'       => $newUsers[$email]['userid'],
                'email'          => $email,
                'k_jenis_sosial' => 4,
            ];

            unset($newUsers[$email]);
        }

        // pastikan user sosialnya sudah ditambahkan
        foreach ($sosialUsers ?: [] as $pasporId => $data) {
            $response = app(User::class)->findUserSosial($pasporId, 4);
            if (!$response) {
                app(User::class)->addAkunSosial($pasporId, $data);
            }
        }

        // tambahkan user yang belum ada di paspor
        if ($newUsers) {
            $response = app(User::class)->addUsersWithSosial(array_values($newUsers));
            foreach ($response as $item) {
                $pasporUsers[$item['email']] = $item;
            }
        }

        if ($miss = array_diff(array_keys($ptkIds), array_keys($pasporUsers))) {
            throw new FlowException("Gagal menambahkan email : " . implode(', ', $miss));
        }

        if ($kKotaSimpatikas) {
            $mKotas = MKota::whereIn('k_kota_simpatika', array_unique($kKotaSimpatikas))->get()->keyBy('k_kota_simpatika');
        } else {
            $mKotas = collect();
        }

        // simpan ptk dengan paspor_id
        foreach ($ptks as $ptk) {
            $kKota = $ptk['ptk_profils'][0]['k_kota'] ?? null;
            if ($mKota->k_kota_simpatika != $kKota) {
                $kKota = $ptk['instansi']['k_kota'] ?? null;
            }

            $ptk = new Ptk($ptk);
            unset($ptk->instansi);
            unset($ptk->ptk_profils);

            /** @var MKota $mKota */
            if ($mKota = $mKotas->get($kKota)) {
                $ptk->k_kota     = $mKota->k_kota;
                $ptk->k_propinsi = $mKota->k_propinsi;
            }

            $paspor = $pasporUsers[$ptk->email];

            $ptk->k_sumber  = 9;// SIMPATIKA
            $ptk->paspor_id = $paspor['userid'];
            $ptk->akun_id   = akunId();

            $ptk->save();
        }
    }
}
