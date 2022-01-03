<?php

namespace App\Http\Controllers\Instansi\Petugas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Luring\Nilai\IndexRequest;
use App\Http\Requests\Instansi\Petugas\Luring\Nilai\SaveRequest;
use App\Http\Resources\BaseCollection;
use App\Models\MVervalPaud;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Services\Instansi\DiklatLuringService;
use App\Services\Instansi\KelasLuringPesertaService;
use App\Services\Instansi\KelasLuringService;
use Symfony\Component\HttpFoundation\Response;

class NilaiController extends Controller
{
    public function index(PaudKelasLuring $kelas, IndexRequest $request)
    {
        $pesertas = app(KelasLuringPesertaService::class)
            ->index($kelas, $request->all())
            ->with([
                'ptk',
                'PaudPesertaNonptk',
            ]);

        [$isPpm, $isPptm] = app(KelasLuringService::class)->getIsPpmOrPptm($kelas, akunId());

        return BaseCollection::make($pesertas->paginate((int)$request->get('count', 10)))
            ->additional([
                'meta' => [
                    'is_ppm'  => $isPpm,
                    'is_pptm' => $isPptm,
                ],
            ]);
    }

    /**
     * @throws FlowException
     */
    public function fetch(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        [$isPpm,] = app(KelasLuringService::class)->validateIsPpmOrPptm($kelas, akunId());

        $results = app(KelasLuringPesertaService::class)
            ->listNilai($kelas, $peserta, $isPpm)
            ->load('mInstrumenNilaiLuringPaud');

        return BaseCollection::make($results);
    }

    /**
     * @throws FlowException
     */
    public function save(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta, SaveRequest $request)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        app(DiklatLuringService::class)->validateSelesai($kelas->paudDiklatLuring);
        app(KelasLuringService::class)->validateLaporanBaru($kelas);

        [$isPpm,] = app(KelasLuringService::class)->validateIsPpmOrPptm($kelas, akunId());

        $results = app(KelasLuringPesertaService::class)
            ->saveNilai($kelas, $peserta, $request->nilai, $isPpm)
            ->load('mInstrumenNilaiLuringPaud');

        return BaseCollection::make($results);
    }

    /**
     * @throws FlowException
     */
    public function delete(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        app(DiklatLuringService::class)->validateSelesai($kelas->paudDiklatLuring);
        app(KelasLuringService::class)->validateLaporanBaru($kelas);

        [$isPpm,] = app(KelasLuringService::class)->validateIsPpmOrPptm($kelas, akunId());

        $results = app(KelasLuringPesertaService::class)
            ->deleteNilai($kelas, $peserta, $isPpm)
            ->load('mInstrumenNilaiLuringPaud');

        return BaseCollection::make($results);
    }
}
