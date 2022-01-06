<?php

namespace App\Http\Controllers\Instansi\Petugas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Luring\Laporan\IndexRequest;
use App\Http\Requests\Instansi\Petugas\Luring\Laporan\UploadRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MInstrumenNilaiLuringPaud;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Models\PaudKelasPesertaLuringNilai;
use App\Services\Instansi\KelasLuringPesertaService;
use App\Services\Instansi\KelasLuringService;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    /**
     * @throws FlowException
     */
    public function upload(PaudKelasLuring $kelas, UploadRequest $request)
    {
        app(KelasLuringService::class)->uploadLaporan($kelas, $request->file);
        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function deleteUpload(PaudKelasLuring $kelas)
    {
        app(KelasLuringService::class)->deleteUpload($kelas);
        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function kirim(PaudKelasLuring $kelas)
    {
        app(KelasLuringService::class)->ajuanLaporan($kelas);
        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function batal(PaudKelasLuring $kelas)
    {
        app(KelasLuringService::class)->batalAjuanLaporan($kelas);
        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelasLuring $kelas, IndexRequest $request)
    {
        $pesertas = app(KelasLuringPesertaService::class)
            ->index($kelas, $request->all())
            ->with([
                'ptk',
                'PaudPesertaNonptk',
            ]);

        return BaseCollection::make($pesertas->paginate((int)$request->get('count', 10)));
    }

    public function nilai(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $mInstruments = MInstrumenNilaiLuringPaud::query()
            ->orderBy('urutan')
            ->get()
            ->keyBy('k_instrumen_nilai_luring_paud');

        $nilais = $peserta->paudKelasPesertaLuringNilais()
            ->get()
            ->keyBy('k_instrumen_nilai_luring_paud');

        $results = PaudKelasPesertaLuringNilai::make()->newCollection();
        foreach ($mInstruments as $kInstrumen => $mInstrument) {
            $results[] = $nilais->get($kInstrumen, fn() => PaudKelasPesertaLuringNilai::make([
                'paud_kelas_peserta_luring_id'  => $kelas->paud_kelas_luring_id,
                'k_instrumen_nilai_luring_paud' => $kInstrumen,
            ]));
        }

        return BaseCollection::make($results->load(['MInstrumenNilaiLuringPaud']));
    }
}
