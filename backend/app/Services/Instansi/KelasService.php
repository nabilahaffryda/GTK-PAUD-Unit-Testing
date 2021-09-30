<?php


namespace App\Services\Instansi;


use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MKonfirmasiPaud;
use App\Models\MKota;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Models\PaudKelasPeserta;
use App\Models\PaudKelasPetugas;
use App\Models\PaudPetugas;
use App\Models\Ptk;
use App\Remotes\Paspor\User;
use App\Remotes\SimpatikaRemote;
use Arr;
use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use League\Flysystem\Util;
use Storage;

class KelasService
{
    public function queryPesertaKandidat(PaudDiklat $paudDiklat, PaudKelas $kelas, int $kJenjang = null, int $kSumber = null)
    {
        return Ptk::query()
            ->whereNotNull('dapodik_ptk_id')
            ->when($kJenjang !== null, function (Builder $query) use ($kJenjang) {
                $query->whereExists(function ($query) use ($kJenjang) {
                    $query->selectRaw(1)
                        ->from('ptk_sekolah')
                        ->join('sekolah', 'sekolah.sekolah_id', '=', 'ptk_sekolah.sekolah_id')
                        ->whereColumn('ptk_sekolah.ptk_id', '=', 'ptk.ptk_id')
                        ->where('sekolah.k_jenjang', '=', $kJenjang);
                });
            })
            ->when($kSumber !== null, function (Builder $query) use ($kSumber) {
                $query->where('k_sumber', '=', $kSumber);
            })
            ->where('k_kota', '=', $paudDiklat->k_kota)
            ->whereDoesntHave('paudKelasPesertas', function (Builder $query) {
                $query->where([
                    'paud_kelas_peserta.tahun'    => config('paud.tahun'),
                    'paud_kelas_peserta.angkatan' => config('paud.angkatan'),
                ]);
            });
    }

    public function queryPetugasKandidat(PaudDiklat $paudDiklat, PaudKelas $kelas, int $kPetugasPaud)
    {
        return PaudPetugas::query()
            ->when($kPetugasPaud == MPetugasPaud::PENGAJAR, function (Builder $query) {
                $query->where('paud_petugas.is_refreshment', '=', 1);
            })
            ->when($kPetugasPaud == MPetugasPaud::PENGAJAR_TAMBAHAN, function (Builder $query) use ($paudDiklat) {
                $query->where('paud_petugas.is_refreshment', '=', 1)
                    ->where('paud_petugas.instansi_id', '=', $paudDiklat->instansi_id);
            })
            ->when($kPetugasPaud == MPetugasPaud::PEMBIMBING_PRAKTIK, function (Builder $query) use ($paudDiklat) {
                $query->where('paud_petugas.is_refreshment', '=', 1)
                    ->where(function (Builder $query) use ($paudDiklat) {
                        $query
                            // PPTM yang ditambahkan oleh LPD hanya bisa dipilih oleh LPD yang menambahkan tersebu
                            ->orWhere('paud_petugas.instansi_id', '=', $paudDiklat->instansi_id)
                            // PPTM inti yang ditambahkan oleh GTK bisa dipilih oleh LPD yang berada satu wilayah kota/kab dengan PPTM nya
                            ->orWhere(function (Builder $query) use ($paudDiklat) {
                                $query->where('paud_petugas.instansi_id', '=', 800006)
                                    ->where('paud_petugas.is_inti', '=', 1)
                                    ->whereHas('akun', function (Builder $query) use ($paudDiklat) {
                                        $query->where('akun.k_kota', '=', $paudDiklat->k_kota);
                                    });
                            });
                    });
            })
            ->when($kPetugasPaud == MPetugasPaud::ADMIN_KELAS, function ($query) use ($paudDiklat) {
                $query->where('paud_petugas.instansi_id', '=', $paudDiklat->instansi_id);
            })
            ->whereDoesntHave('paudKelasPetugases', function (Builder $query) use ($paudDiklat, $kelas, $kPetugasPaud) {
                $query->when($kPetugasPaud == MPetugasPaud::ADMIN_KELAS, function ($query) use ($kelas, $kPetugasPaud) {
                    $query->where('k_petugas_paud', '=', $kPetugasPaud)
                        ->where('paud_kelas_id', '=', $kelas->paud_kelas_id);
                });
            })
            ->where('paud_petugas.k_petugas_paud', '=', $kPetugasPaud);
    }

    public function index(PaudDiklat $paudDiklat, array $params): Builder
    {
        $query = PaudKelas::query()
            ->where('paud_kelas.paud_diklat_id', $paudDiklat->paud_diklat_id)
            ->with(['mKelurahan', 'mKecamatan', 'mVervalPaud']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where('paud_kelas.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function create(PaudDiklat $paudDiklat, array $params): PaudKelas
    {
        $kelas                 = new PaudKelas($params);
        $kelas->tahun          = $paudDiklat->tahun;
        $kelas->angkatan       = $paudDiklat->angkatan;
        $kelas->paud_diklat_id = $paudDiklat->paud_diklat_id;
        $kelas->k_verval_paud  = MVervalPaud::KANDIDAT;
        $kelas->created_by     = akunId();

        if (!$kelas->save()) {
            throw new SaveException("Proses Tambah Kelas Tidak berhasil");
        }

        return $kelas->load(['mVervalPaud', 'paudDiklat', 'paudMapelKelas']);
    }

    public function update(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);

        $kelas->fill($params);
        $kelas->updated_by = akunId();
        if (!$kelas->save()) {
            throw new SaveException("Proses simpan data kelas tida berhasil");
        }

        return $kelas;
    }

    public function fetch(PaudDiklat $paudDiklat, PaudKelas $kelas): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);

        return $kelas->load(['mVervalPaud', 'paudDiklat.instansi', 'paudDiklat.paudInstansi', 'paudMapelKelas']);
    }

    /**
     * @throws FlowException
     */
    public function validateKelas(PaudDiklat $diklat, PaudKelas $kelas)
    {
        if ($kelas->paud_diklat_id <> $diklat->paud_diklat_id) {
            throw new FlowException('Kelas tidak ditemukan');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateKelasBaru(PaudKelas $kelas)
    {
        if ($kelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Kelas sudah diajukan/diverval');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateKelasAjuan(PaudKelas $kelas)
    {
        if ($kelas->k_verval_paud == MVervalPaud::KANDIDAT) {
            throw new FlowException('Kelas belum diajukan');
        }

        if (!in_array($kelas->k_verval_paud, [MVervalPaud::DIAJUKAN, MVervalPaud::REVISI])) {
            throw new FlowException('Kelas sudah diverval');
        }
    }

    public function indexPeserta(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Builder
    {
        $this->validateKelas($paudDiklat, $kelas);

        $query = PaudKelasPeserta::query()
            ->where('paud_kelas_peserta.paud_kelas_id', '=', $kelas->paud_kelas_id)
            ->with(['ptk:ptk_id,nama,email', 'mKonfirmasiPaud']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('ptk', 'ptk.ptk_id', '=', 'paud_kelas_peserta.ptk_id')
                ->where('ptk.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function indexPetugas(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Builder
    {
        $this->validateKelas($paudDiklat, $kelas);

        $query = PaudKelasPetugas::query()
            ->where('paud_kelas_petugas.paud_kelas_id', '=', $kelas->paud_kelas_id)
            ->where('paud_kelas_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
            ->with([
                'akun',
                'akun.mKota',
                'akun.mPropinsi',
                'mKonfirmasiPaud',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('akun', 'akun.akun_id', '=', 'paud_kelas_petugas.akun_id')
                ->where('akun.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    /**
     * @throws FlowException
     */
    public function indexPesertaKandidat(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params, int $kJenjang)
    {
        $this->validateKelas($paudDiklat, $kelas);

        return $this
            ->queryPesertaKandidat($paudDiklat, $kelas, kJenjang: $kJenjang)
            ->when(Arr::get($params, 'keyword'), function (Builder $query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query
                        ->orWhere('ptk.ptk_id', '=', $keyword)
                        ->orWhere('ptk.nama', 'like', '%' . $keyword . '%')
                        ->orWhere('ptk.email', 'like', '%' . $keyword . '%');
                });
            });
    }

    /**
     * @throws FlowException
     * @throws GuzzleException
     */
    public function indexPesertaKandidatSimpatika(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params)
    {
        $this->validateKelas($paudDiklat, $kelas);

        $result = app(SimpatikaRemote::class)
            ->searchGuruRA(
                $params['keyword'] ?? '',
                $params['page'] ?? 1,
                $params['count'] ?? 10,
            );

        $data   = collect($result['data'] ?? []);
        $ptkIds = $data->pluck('ptk_id')->unique();

        $pesertas = PaudKelasPeserta::query()
            ->where([
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ])
            ->whereIn('ptk_id', $ptkIds)
            ->get()
            ->keyBy('ptk_id');

        foreach ($result['data'] ?? [] as $index => $data) {
            $ptkId = $data['ptk_id'] ?? null;

            $result['data'][$index]['is_baru'] = !($ptkId && $pesertas->has($ptkId));
        }

        return $result;
    }

    public function indexPetugasKandidat(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params)
    {
        $this->validateKelas($paudDiklat, $kelas);

        $query = $this
            ->queryPetugasKandidat($paudDiklat, $kelas, $params['k_petugas_paud'])
            ->with(['akun:akun_id,nama,email']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('akun', 'akun.akun_id', '=', 'paud_petugas.akun_id')
                ->where(function ($query) use ($keyword) {
                    $query
                        ->orWhere('akun.nama', 'like', '%' . $keyword . '%')
                        ->orWhere('akun.email', 'like', '%' . $keyword . '%');
                });
        }

        return $query;
    }

    /**
     * @throws FlowException
     */
    public function createPeserta(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params, int $kJenjang = null, int $kSumber = null): Collection
    {
        $this->validateKelas($paudDiklat, $kelas);
        $this->validateKelasBaru($kelas);

        $ptks = $this
            ->queryPesertaKandidat($paudDiklat, $kelas, kJenjang: $kJenjang, kSumber: $kSumber)
            ->whereIn('ptk_id', $params['ptk_id'])
            ->get();

        if ($diff = array_diff($params['ptk_id'], $ptks->pluck('ptk_id')->all())) {
            $ptkIds = implode(', ', $diff);

            throw new FlowException("Peserta dengan PTK_ID {$ptkIds} tidak ditemukan");
        }

        $jmlPeserta = PaudKelasPeserta::query()
            ->where([
                'paud_kelas_peserta.paud_kelas_id' => $kelas->paud_kelas_id,
            ])
            ->count();

        $jmlPeserta += count($params['ptk_id']);
        if ($jmlPeserta > 40) {
            throw new FlowException("Jumlah peserta maksimal 40 orang");
        }

        /** @var Ptk $ptk */
        foreach ($ptks as $ptk) {
            $paudKelasPeserta = new PaudKelasPeserta();
            $paudKelasPeserta->fill($params);
            $paudKelasPeserta->paud_kelas_id     = $kelas->paud_kelas_id;
            $paudKelasPeserta->tahun             = $kelas->tahun;
            $paudKelasPeserta->angkatan          = $kelas->angkatan;
            $paudKelasPeserta->ptk_id            = $ptk->ptk_id;
            $paudKelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
            $paudKelasPeserta->created_by        = akunId();

            if (!$paudKelasPeserta->save()) {
                throw new FlowException("Peserta tidak berhasil disimpan");
            }
        }

        return $ptks;
    }

    /**
     * @throws FlowException
     * @throws GuzzleException
     */
    public function createPesertaSimpatika(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Collection
    {
        $ptkIds = $params['ptk_id'];

        $ptks = Ptk::query()
            ->whereIn('ptk_id', $params['ptk_id'])
            ->get()
            ->keyBy('ptk_id');

        $kKotaSimpatikas = [];

        $newPtkIds = array_diff($ptkIds, $ptks->keys()->all());
        if ($newPtkIds) {
            $ptks = app(SimpatikaRemote::class)->fetchGuruRA($newPtkIds);

            // simpan ke paspor
            $ptkIds   = [];
            $newUsers = [];
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

        return $this->createPeserta($paudDiklat, $kelas, $params, kSumber: 9);
    }

    /**
     * @throws FlowException
     */
    public function createPetugas(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Collection
    {
        $this->validateKelas($paudDiklat, $kelas);
        $this->validateKelasBaru($kelas);

        $jmlPetugas = PaudKelasPetugas::query()
            ->where([
                'paud_kelas_petugas.paud_kelas_id' => $kelas->paud_kelas_id,
            ])
            ->whereIn('paud_kelas_petugas.k_petugas_paud', [$params['k_petugas_paud']])
            ->count();

        $jmlPetugas += count($params['akun_id']);

        $paudInstansi = $paudDiklat->paudInstansi;

        $maxPengajarTambahan = floor($kelas->jml_pengajar * $paudInstansi->ratio_pengajar_tambahan / 100);

        $batasan = [
            MPetugasPaud::PENGAJAR           => $kelas->jml_pengajar,
            MPetugasPaud::PENGAJAR_TAMBAHAN  => $maxPengajarTambahan,
            MPetugasPaud::PEMBIMBING_PRAKTIK => $paudInstansi->jml_pembimbing,
            MPetugasPaud::ADMIN_KELAS        => 1,
        ];

        if (isset($batasan[$params['k_petugas_paud']]) && $jmlPetugas > $batasan[$params['k_petugas_paud']]) {
            throw new FlowException("Jumlah petugas maksimal adalah {$batasan[$params['k_petugas_paud']]} orang");
        }

        if (in_array($params['k_petugas_paud'], [MPetugasPaud::PENGAJAR, MPetugasPaud::PENGAJAR_TAMBAHAN])) {
            $jmlPetugasLain = PaudKelasPetugas::query()
                ->where([
                    'paud_kelas_petugas.paud_kelas_id'  => $kelas->paud_kelas_id,
                    'paud_kelas_petugas.k_petugas_paud' => $params['k_petugas_paud'] == MPetugasPaud::PENGAJAR
                        ? MPetugasPaud::PENGAJAR_TAMBAHAN : MPetugasPaud::PENGAJAR,
                ])
                ->count();

            $jmlPengajar = $jmlPetugasLain + $jmlPetugas;
            if ($jmlPengajar > $kelas->jml_pengajar) {
                throw new FlowException("Jumlah pengajar dan pengajar tambahan maksimal adalah {$kelas->jml_pengajar} orang");
            }
        }

        $paudPetugases = $this->queryPetugasKandidat($paudDiklat, $kelas, $params['k_petugas_paud'])
            ->whereIn('akun_id', $params['akun_id'])
            ->get();

        if ($diff = array_diff($params['akun_id'], $paudPetugases->pluck('akun_id')->all())) {
            $akuns = Akun::whereIn('akun_id', $diff)->pluck('email')->unique()->all();
            $namas = implode(', ', $akuns);

            throw new FlowException("Petugas dengan email {$namas} tidak ditemukan");
        }

        /** @var PaudPetugas $petugas */
        foreach ($paudPetugases as $petugas) {
            $paudKelasPetugas = new PaudKelasPetugas();
            $paudKelasPetugas->fill($params);
            $paudKelasPetugas->paud_petugas_id   = $petugas->paud_petugas_id;
            $paudKelasPetugas->paud_kelas_id     = $kelas->paud_kelas_id;
            $paudKelasPetugas->tahun             = $kelas->tahun;
            $paudKelasPetugas->angkatan          = $kelas->angkatan;
            $paudKelasPetugas->akun_id           = $petugas->akun_id;
            $paudKelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
            $paudKelasPetugas->created_by        = akunId();

            if (!$paudKelasPetugas->save()) {
                throw new FlowException("Petugas tidak berhasil disimpan");
            }
        }

        return $paudPetugases->load('akun:akun_id,nama,email');
    }

    /**
     * @throws FlowException
     */
    public function ajuan(PaudDiklat $paudDiklat, PaudKelas $kelas): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);
        $this->validateKelasBaru($kelas);

        $tidakBersedia = $kelas
            ->paudKelasPetugases()
            ->whereNotIn('k_petugas_paud', [MPetugasPaud::ADMIN_KELAS])
            ->whereNotIn('k_konfirmasi_paud', [MKonfirmasiPaud::BERSEDIA])
            ->exists();

        if ($tidakBersedia) {
            throw new FlowException("Masih ada petugas yang belum bersedia/belum konfirmasi");
        }

        $tidakBersedia = $kelas
            ->paudKelasPesertas()
            ->whereNotIn('k_konfirmasi_paud', [MKonfirmasiPaud::BERSEDIA])
            ->exists();

        if ($tidakBersedia) {
            throw new FlowException("Masih ada peserta yang belum bersedia/belum konfirmasi");
        }

        $jmlPetugases = PaudKelasPetugas::query()
            ->where([
                'paud_kelas_id' => $kelas->paud_kelas_id,
            ])
            ->groupBy('k_petugas_paud')
            ->get(['k_petugas_paud', DB::raw('count(1) jumlah')])
            ->pluck('jumlah', 'k_petugas_paud');

        $paudInstansi = $paudDiklat->paudInstansi;

        $maxPengajarTambahan = floor($kelas->jml_pengajar * $paudInstansi->ratio_pengajar_tambahan / 100);
        $minPengajar         = $kelas->jml_pengajar - $maxPengajarTambahan;

        $batasan = [
            MPetugasPaud::PENGAJAR           => [$minPengajar, $kelas->jml_pengajar],
            MPetugasPaud::PENGAJAR_TAMBAHAN  => [0, $maxPengajarTambahan],
            MPetugasPaud::PEMBIMBING_PRAKTIK => [min(4, $paudInstansi->jml_pembimbing), $paudInstansi->jml_pembimbing],
            MPetugasPaud::ADMIN_KELAS        => [1, 1],
        ];

        foreach ($batasan as $kPetugasPaud => $jml) {
            [$jmlMin, $jmlMax] = $jml;

            if ($jmlPetugases->get($kPetugasPaud, 0) < $jmlMin) {
                $mPetugasPaud = MPetugasPaud::find($kPetugasPaud);
                throw new FlowException("Petugas {$mPetugasPaud->keterangan} minimal {$jmlMin} orang");
            }

            if ($jmlPetugases->get($kPetugasPaud, 0) > $jmlMax) {
                $mPetugasPaud = MPetugasPaud::find($kPetugasPaud);
                throw new FlowException("Petugas {$mPetugasPaud->keterangan} maksimal {$jmlMin} orang");
            }
        }

        $jmlPengajar = $jmlPetugases->get(MPetugasPaud::PENGAJAR, 0);
        $jmlTambahan = $jmlPetugases->get(MPetugasPaud::PENGAJAR_TAMBAHAN, 0);
        if (($jmlPengajar + $jmlTambahan) > $kelas->jml_pengajar) {
            throw new FlowException("Pengajar dan pengajar tambahan maksimal {$kelas->jml_pengajar} orang");
        }

        $jmlPeserta = PaudKelasPeserta::query()
            ->where([
                'paud_kelas_peserta.paud_kelas_id' => $kelas->paud_kelas_id,
            ])
            ->count();

        if ($jmlPeserta < 20) {
            throw new FlowException("Jumlah peserta minimal 20 orang");
        }

        if ($jmlPeserta > 40) {
            throw new FlowException("Jumlah peserta maksimal 40 orang");
        }

        $kelas->wkt_ajuan     = Carbon::now();
        $kelas->k_verval_paud = MVervalPaud::DIAJUKAN;

        if (!$kelas->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $kelas;
    }

    public function batalAjuan(PaudDiklat $paudDiklat, PaudKelas $kelas): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);
        $this->validateKelasAjuan($kelas);

        $kelas->wkt_ajuan     = null;
        $kelas->k_verval_paud = MVervalPaud::KANDIDAT;

        if (!$kelas->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     */
    public function verval(Akun $akun, PaudKelas $kelas, array $params): PaudKelas
    {
        if (in_array($kelas->k_verval_paud, [MVervalPaud::DITOLAK, MVervalPaud::REVISI, MVervalPaud::DISETUJUI])) {
            throw new FlowException('Kelas sudah diverval');
        }

        if ($kelas->k_verval_paud != MVervalPaud::DIAJUKAN) {
            throw new FlowException('Kelas belum diajukan');
        }

        $kelas->k_verval_paud  = $params['k_verval_paud'];
        $kelas->wkt_verval     = Carbon::now();
        $kelas->akun_id_verval = $akun->akun_id;
        $kelas->alasan         = $params['alasan'] ?? null;

        if (!$kelas->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     */
    public function batalVerval(Akun $akun, PaudKelas $kelas): PaudKelas
    {
        if ($kelas->k_verval_paud == MVervalPaud::DIAJUKAN) {
            throw new FlowException('Kelas masih diajukan');
        }

        if (!in_array($kelas->k_verval_paud, [MVervalPaud::DITOLAK, MVervalPaud::REVISI, MVervalPaud::DISETUJUI])) {
            throw new FlowException('Kelas belum diverval');
        }

        $kelas->k_verval_paud  = MVervalPaud::DIAJUKAN;
        $kelas->wkt_verval     = Carbon::now();
        $kelas->akun_id_verval = $akun->akun_id;
        $kelas->alasan         = null;

        if (!$kelas->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     * @throws Exception
     */
    public function uploadJadwal(PaudDiklat $diklat, PaudKelas $kelas, UploadedFile $file)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['pdf', 'jpeg', 'jpg', 'png'])) {
            throw new FlowException("Jenis berkas jadwal tidak dikenali");
        }

        $ftpPath   = config('filesystems.disks.kelas-jadwal.path');
        $timestamp = date('ymdhis');
        $random    = random_int(10000, 99999);
        $name      = Util::normalizePath($file->getClientOriginalName());
        $filename  = "{$diklat->instansi_id}/{$kelas->paud_kelas_id}-{$timestamp}-{$random}";

        $path = sprintf("%s/%s", $ftpPath, $filename);
        if (!Storage::disk('instansi-foto')->putFileAs($path, $file, $name)) {
            throw new FlowException("Unggah berkas jadwal tidak berhasil");
        }

        $filename = "{$filename}/{$name}";

        $kelas->file_jadwal = $filename;
        $kelas->save();

        // old file TIDAK DIHAPUS untuk digunakan sebagai backup

        return $filename;
    }
}
