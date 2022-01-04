<?php

namespace App\Http\Controllers\Instansi\Kelas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPeserta;
use App\Models\PaudKelasPesertaLuring;
use App\Models\PaudKelasPetugas;
use App\Services\Instansi\KelasLuringService;
use Arr;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class VervalController extends Controller
{
    public function __construct(
        protected KelasLuringService $service,
    )
    {
    }

    public function index(Request $request)
    {
        $params = $request->all();

        $q = PaudKelasLuring::query()
            ->where([
                'tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                'angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->when($params['filter']['k_verval_paud'] ?? null, function ($query, $value) {
                $query->whereIn('k_verval_paud', (array)$value);
            }, function ($query) {
                $query->whereNotIn('k_verval_paud', [MVervalPaud::KANDIDAT]);
            })
            ->when(($params['filter']['tgl_mulai'] ?? null) || ($params['filter']['tgl_selesai'] ?? null), function ($query) use ($params) {
                $query->whereHas('paudDiklatLuring', function ($query) use ($params) {
                    $query
                        ->when($params['filter']['tgl_mulai'] ?? null, function ($query, $value) {
                            $query->where('tgl_mulai', '>=', $value);
                        })
                        ->when($params['filter']['tgl_selesai'] ?? null, function ($query, $value) {
                            $query->where('tgl_selesai', '<=', $value);
                        });
                });
            })
            ->when($request->keyword, function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query
                        ->orWhere('nama', 'like', "%$value%")
                        ->orWhereHas('paudDiklatLuring', function ($query) use ($value) {
                            $query
                                ->join('instansi', 'instansi.instansi_id', '=', 'paud_diklat_luring.instansi_id')
                                ->where([
                                    ['paud_diklat_luring.nama', 'like', "%$value%", 'or'],
                                    ['instansi.nama', 'like', "%$value%", 'or'],
                                ]);
                        });
                });
            })
            ->with([
                'mVervalPaud',
                'paudDiklatLuring.Instansi',
                'paudDiklatLuring.paudInstansi',
                'paudMapelKelas',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function fetch(PaudKelasLuring $kelas)
    {
        $kelas->load([
            'mVervalPaud',
            'paudDiklatLuring.Instansi',
            'paudDiklatLuring.paudInstansi',
            'paudDiklatLuring.mPropinsi',
            'paudDiklatLuring.mKota',
            'paudMapelKelas',
            'mKecamatan',
            'mKelurahan',
        ]);

        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelasLuring $kelas, Request $request)
    {
        $q = $kelas->paudKelasPesertaLurings()
            ->with([
                'ptk',
                'paudPesertaNonptk',
                'mKonfirmasiPaud',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function pesertaDetil(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(404);
        }

        $peserta
            ->load([
                'mKonfirmasiPaud',
                'paudKelasLuring',
                'paudPesertaNonptk',
                'paudPesertaNonptk.mDiklatPaud',
                'paudPesertaNonptk.mJenjangDiklatPaud',
                'paudPesertaNonptk.mKecamatan',
                'paudPesertaNonptk.mKota',
                'paudPesertaNonptk.mPropinsi',
                'ptk',
            ]);

        return BaseResource::make($peserta);
    }

    public function petugas(PaudKelasLuring $kelas, Request $request)
    {
        $q = $kelas->paudKelasPetugasLurings()
            ->where([
                'k_petugas_paud' => $request->get('k_petugas_paud'),
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
    public function updateVerval(PaudKelasLuring $kelas, Request $request)
    {
        $kelas = $this->service->verval(akun(), $kelas, $request->all());
        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function batalVerval(PaudKelasLuring $kelas)
    {
        $kelas = $this->service->batalVerval(akun(), $kelas);
        return BaseResource::make($kelas);
    }

    public function download(Request $request)
    {
        $params = $request->all();

        $q = PaudKelasLuring::query()
            ->addSelect([
                'jml_peserta'      => PaudKelasPeserta::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_peserta.paud_kelas_id', 'paud_kelas.paud_kelas_id'),
                'jml_admin_kelas'  => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::ADMIN_KELAS),
                'jml_pptm'         => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::PEMBIMBING_PRAKTIK),
                'jml_ppm'          => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::PENGAJAR),
                'jml_ppm_tambahan' => PaudKelasPetugas::selectRaw('COUNT(1)')
                    ->whereColumn('paud_kelas_petugas.paud_kelas_id', 'paud_kelas.paud_kelas_id')
                    ->where('paud_kelas_petugas.k_petugas_paud', MPetugasPaud::PENGAJAR_TAMBAHAN),
            ])
            ->where([
                'tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                'angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->when($params['filter']['k_verval_paud'] ?? null, function ($query, $value) {
                $query->whereIn('paud_kelas.k_verval_paud', (array)$value);
            }, function ($query) {
                $query->whereNotIn('paud_kelas.k_verval_paud', [MVervalPaud::KANDIDAT]);
            })
            ->when($params['filter']['tgl_mulai'] ?? null || $params['filter']['tgl_selesai'] ?? null, function ($query) use ($params) {
                $query->whereHas('paudDiklatLuring', function ($query) use ($params) {
                    $query
                        ->when($params['filter']['tgl_mulai'] ?? null, function ($query, $value) {
                            $query->where('tgl_mulai', '>=', $value);
                        })
                        ->when($params['filter']['tgl_selesai'] ?? null, function ($query, $value) {
                            $query->where('tgl_selesai', '<=', $value);
                        });
                });
            })
            ->when($request->keyword, function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query
                        ->orWhere('paud_kelas.nama', 'like', "%$value%")
                        ->orWhereHas('paudDiklatLuring', function ($query) use ($value) {
                            $query
                                ->join('instansi', 'instansi.instansi_id', '=', 'paud_diklat_luring.instansi_id')
                                ->where([
                                    ['paud_diklat_luring.nama', 'like', "%$value%", 'or'],
                                    ['instansi.nama', 'like', "%$value%", 'or'],
                                ]);
                        });
                });
            })
            ->with([
                'mVervalPaud',
                'paudDiklatLuring.Instansi',
                'paudMapelKelas',
            ]);

        if (Arr::get($params, 'format') == 'json') {
            return $q->paginate(10);
        }

        $header = [
            'NO',
            'INSTANSI LPD',
            'NAMA KELAS',
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
        $filename = "Laporan-Verval-Kelas-Diklat-Luring-{$date}.xlsx";

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
            /** @var PaudKelasLuring[]|Collection $kelases */
            foreach ($kelases as $kelas) {
                $mVerval  = $kelas->mVervalPaud;
                $diklat   = $kelas->paudDiklatLuring;
                $mapel    = $kelas->paudMapelKelas;
                $instansi = $diklat->instansi;

                /** @var PaudKelasLuring $kelas */
                $row = [
                    $i++,
                    $instansi->nama,
                    $mapel->nama . ' - ' . $kelas->nama,
                    $kelas->paudDiklatLuring->tgl_mulai->format('d MMMM YYYY'),
                    $kelas->paudDiklatLuring->tgl_selesai->format('d MMMM YYYY'),
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
