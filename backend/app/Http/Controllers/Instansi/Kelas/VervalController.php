<?php

namespace App\Http\Controllers\Instansi\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelas;
use App\Models\PaudKelasPeserta;
use App\Models\PaudKelasPetugas;
use App\Services\Instansi\KelasService;
use App\Services\Instansi\PeriodeService;
use Arr;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class VervalController extends Controller
{
    public function __construct(
        protected KelasService $service,
    )
    {
    }

    public function index(Request $request)
    {
        $params = $request->all();

        $q = PaudKelas::query()
            ->where([
                'paud_kelas.tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                'paud_kelas.angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->when($params['filter']['k_verval_paud'] ?? null, function ($query, $value) {
                $query->whereIn('paud_kelas.k_verval_paud', (array)$value);
            }, function ($query) {
                $query->whereNotIn('paud_kelas.k_verval_paud', [MVervalPaud::KANDIDAT]);
            })
            ->when($params['filter']['paud_periode_id'] ?? null, function ($query, $value) {
                $query->whereHas('paudDiklat', function ($query) use ($value) {
                    $query->where('paud_periode_id', '=', $value);
                });
            })
            ->when($request->keyword, function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query
                        ->orWhere('paud_kelas.nama', 'like', "%$value%")
                        ->orWhereHas('paudDiklat', function ($query) use ($value) {
                            $query
                                ->join('instansi', 'instansi.instansi_id', '=', 'paud_diklat.instansi_id')
                                ->where([
                                    ['paud_diklat.nama', 'like', "%$value%", 'or'],
                                    ['instansi.nama', 'like', "%$value%", 'or'],
                                ]);
                        });
                });
            })
            ->with([
                'mVervalPaud',
                'paudDiklat.Instansi',
                'paudDiklat.paudInstansi',
                'paudDiklat.paudPeriode',
                'paudMapelKelas',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function periode()
    {
        return BaseCollection::make(app(PeriodeService::class)->index()->get());
    }

    public function fetch(PaudKelas $kelas)
    {
        $kelas->load([
            'mVervalPaud',
            'paudDiklat.Instansi',
            'paudDiklat.paudInstansi',
            'paudDiklat.paudPeriode',
            'paudDiklat.mPropinsi',
            'paudDiklat.mKota',
            'paudMapelKelas',
            'mKecamatan',
            'mKelurahan',
        ]);

        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelas $kelas, Request $request)
    {
        $q = $kelas->paudKelasPesertas()
            ->with([
                'ptk',
                'mKonfirmasiPaud',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function petugas(PaudKelas $kelas, Request $request)
    {
        $q = $kelas->paudKelasPetugases()
            ->where([
                'k_petugas_paud' => $request->get('k_petugas_paud'),
                // 'k_konfirmasi_paud' => MKonfirmasiPaud::BERSEDIA,
            ])
            ->with([
                'akun',
                'akun.mKota',
                'akun.mPropinsi',
                'paudPetugas',
                'mKonfirmasiPaud',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    /**
     * @throws FlowException
     */
    public function updateVerval(PaudKelas $kelas, Request $request)
    {
        $kelas = $this->service->verval(akun(), $kelas, $request->all());
        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function batalVerval(PaudKelas $kelas)
    {
        $kelas = $this->service->batalVerval(akun(), $kelas);
        return BaseResource::make($kelas);
    }

    public function download(Request $request)
    {
        $params = $request->all();

        $q = PaudKelas::query()
            ->addSelect([
                'jml_peserta'     => PaudKelasPeserta::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_peserta.paud_kelas_id', 'paud_kelas.paud_kelas_id'),
                'jml_admin_kelas' => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::ADMIN_KELAS),
                'jml_pptm' => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::PEMBIMBING_PRAKTIK),
                'jml_ppm' => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::PENGAJAR),
                'jml_ppm_tambahan' => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::PENGAJAR_TAMBAHAN),
            ])
            ->where([
                'paud_kelas.tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                'paud_kelas.angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->when($params['filter']['k_verval_paud'] ?? null, function ($query, $value) {
                $query->whereIn('paud_kelas.k_verval_paud', (array)$value);
            }, function ($query) {
                $query->whereNotIn('paud_kelas.k_verval_paud', [MVervalPaud::KANDIDAT]);
            })
            ->when($params['filter']['paud_periode_id'] ?? null, function ($query, $value) {
                $query->whereHas('paudDiklat', function ($query) use ($value) {
                    $query->where('paud_periode_id', '=', $value);
                });
            })
            ->when($request->keyword, function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query
                        ->orWhere('paud_kelas.nama', 'like', "%$value%")
                        ->orWhereHas('paudDiklat', function ($query) use ($value) {
                            $query
                                ->join('instansi', 'instansi.instansi_id', '=', 'paud_diklat.instansi_id')
                                ->where([
                                    ['paud_diklat.nama', 'like', "%$value%", 'or'],
                                    ['instansi.nama', 'like', "%$value%", 'or'],
                                ]);
                        });
                });
            })
            ->with([
                'mVervalPaud',
                'paudDiklat.Instansi',
                'paudDiklat.paudPeriode',
                'paudMapelKelas',
            ]);

        if (Arr::get($params, 'format') == 'json') {
            return $q->paginate(10);
        }

        $header = [
            'NO',
            'INSTANSI LPD',
            'NAMA KELAS',
            'ANGKATAN',
            'TGL MULAI',
            'TGL SELESAI',
            'JML PESERTA',
            'JML ADMIN KELAS',
            'JML PPTM',
            'JML PPM',
            'JML PPM TAMBAHAN',
            'PROVINSI',
            'KOTA/KAB',
            'KECAMATAN',
            'KELURAHAN',
            'STATUS',
            'CATATAN',
        ];

        $date     = Carbon::now()->format('dmYHi');
        $filename = "Laporan-Verval-Kelas-Diklat-{$date}.xlsx";

        $defaultStyle = (new StyleBuilder())
            ->setFontName('Calibri')
            ->setFontSize(11)
            ->setShouldWrapText(false)
            ->build();
        $headerStyle  = clone $defaultStyle;
        $headerStyle->setFontBold();

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($filename);
        $writer->setDefaultRowStyle($defaultStyle);

        $writer->addRow(WriterEntityFactory::createRowFromArray($header, $headerStyle));

        $i = 1;
        $q->chunk(1000, function ($kelases) use ($writer, &$i) {
            /** @var PaudKelas[]|Collection $kelases */
            foreach ($kelases as $kelas) {
                $mVerval  = $kelas->mVervalPaud;
                $diklat   = $kelas->paudDiklat;
                $mapel    = $kelas->paudMapelKelas;
                $instansi = $diklat->instansi;
                $periode  = $diklat->paudPeriode;

                /** @var PaudKelas $kelas */
                $row = [
                    $i++,
                    $instansi->nama,
                    $mapel->nama . ' - ' . $kelas->nama,
                    $periode->nama,
                    $periode->tgl_diklat_mulai->format('d MMMM YYYY'),
                    $periode->tgl_diklat_selesai->format('d MMMM YYYY'),
                    $kelas->jml_peserta,
                    $kelas->jml_admin_kelas,
                    $kelas->jml_pptm,
                    $kelas->jml_ppm,
                    $kelas->jml_ppm_tambahan,
                    $diklat->mPropinsi->keterangan,
                    $diklat->mKota->keterangan,
                    $kelas->mKecamatan->keterangan,
                    $kelas->mKelurahan->keterangan,
                    $mVerval->keterangan,
                    $kelas->k_verval_paud == MVervalPaud::REVISI ? $kelas->catatan : '',
                ];

                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }
        });

        $writer->close();
        exit(0);
    }
}
