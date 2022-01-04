<?php

namespace App\Http\Controllers\Instansi\Petugas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Luring\Laporan\IndexRequest;
use App\Http\Requests\Instansi\Petugas\Luring\Laporan\UploadRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
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

        $nilais = $peserta->paudKelasPesertaLuringNilais()
            ->select('paud_kelas_peserta_luring_nilai.*')
            ->join('m_instrumen_nilai_luring_paud', 'm_instrumen_nilai_luring_paud.k_instrumen_nilai_luring_paud', '=', 'paud_kelas_peserta_luring_nilai.k_instrumen_nilai_luring_paud')
            ->orderBy('m_instrumen_nilai_luring_paud.urutan')
            ->with('MInstrumenNilaiLuringPaud')
            ->get();

        return BaseCollection::make($nilais);
    }
}
