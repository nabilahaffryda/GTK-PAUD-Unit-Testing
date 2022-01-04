<?php

namespace App\Http\Controllers\Instansi\Kelas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Kelas\Luring\Laporan\IndexRequest;
use App\Http\Requests\Instansi\Kelas\Luring\Laporan\VervalRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MVervalPaud;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Services\Instansi\KelasLuringService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(
        protected KelasLuringService $service,
    )
    {
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make(PaudKelasLuring::query()
            ->where([
                'tahun'    => $request->tahun ?: config('paud.tahun'),
                'angkatan' => $request->angkatan ?: config('paud.angkatan'),
            ])
            ->when($request->filter['laporan_k_verval_paud'] ?? null, function ($query, $value) {
                $query->whereIn('laporan_k_verval_paud', (array)$value);
            }, function ($query) {
                $query->whereNotIn('laporan_k_verval_paud', [MVervalPaud::KANDIDAT]);
            })
            ->when(($request->filter['tgl_mulai'] ?? null) || ($request->filter['tgl_selesai'] ?? null), function ($query) use ($request) {
                $query->whereHas('paudDiklatLuring', function ($query) use ($request) {
                    $query
                        ->when($request->filter['tgl_mulai'] ?? null, function ($query, $value) {
                            $query->where('tgl_mulai', '>=', $value);
                        })
                        ->when($request->filter['tgl_selesai'] ?? null, function ($query, $value) {
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
                'laporanVervalPaud',
                'paudDiklatLuring.Instansi',
                'paudDiklatLuring.paudInstansi',
                'paudMapelKelas',
            ])
            ->paginate($request->count ?: 10)
        );
    }

    public function fetch(PaudKelasLuring $kelas)
    {
        $kelas->load([
            'laporanVervalPaud',
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
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function peserta(PaudKelasLuring $kelas, Request $request)
    {
        $q = $kelas->paudKelasPesertaLurings()
            ->with([
                'ptk',
                'paudPesertaNonptk',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function pesertaNilai(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(404);
        }

        $peserta
            ->load([
                'paudKelasLuring',
                'paudPesertaNonptk',
                'ptk',
                'paudKelasPesertaLuringNilais',
            ]);

        return BaseResource::make($peserta);
    }

    /**
     * @throws FlowException
     */
    public function updateVerval(PaudKelasLuring $kelas, VervalRequest $request)
    {
        return BaseResource::make($this->service->vervalLaporan(akun(), $kelas, $request->validated()));
    }

    public function batalVerval(PaudKelasLuring $kelas)
    {
        return BaseResource::make($this->service->batalVervalLaporan(akun(), $kelas));
    }
}
