<?php

namespace App\Http\Controllers\Instansi\Lpd\Luring\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\CreatePesertaNonPtkRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\CreatePesertaRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\IndexPesertaRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Models\PaudDiklatLuring;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Services\Instansi\KelasLuringService;
use GuzzleHttp\Exception\GuzzleException;

class PesertaController extends Controller
{
    public function __construct(
        protected KelasLuringService $service,
    )
    {
    }

    /**
     * @throws FlowException
     */
    public function index(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->indexPeserta($diklat, $kelas, $request->validated())
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function candidate(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexPesertaRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->indexPesertaKandidat($diklat, $kelas, $request->validated(), kJenjang: 1)
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function candidateSimpatika(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexPesertaRequest $request)
    {
        return $this
            ->service
            ->indexPesertaKandidatSimpatika($diklat, $kelas, $request->validated());
    }

    /**
     * @throws FlowException
     */
    public function candidateSd(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexPesertaRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->indexPesertaKandidat($diklat, $kelas, $request->validated(), 2)
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function candidateNonPtk(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexPesertaRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->indexPesertaKandidatNonPtk($diklat, $kelas, $request->validated())
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function create(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, CreatePesertaRequest $request)
    {
        $paudPesertas = $this->service->createPeserta($diklat, $kelas, $request->validated(), kJenjang: 1);

        return BaseCollection::make($paudPesertas);
    }

    /**
     * @throws FlowException
     * @throws GuzzleException
     */
    public function createSimpatika(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, CreatePesertaRequest $request)
    {
        $paudPesertas = $this->service->createPesertaSimpatika($diklat, $kelas, $request->validated());

        return BaseCollection::make($paudPesertas);
    }

    /**
     * @throws FlowException
     */
    public function createSd(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, CreatePesertaRequest $request)
    {
        $paudPesertas = $this->service->createPeserta($diklat, $kelas, $request->validated(), kJenjang: 2);

        return BaseCollection::make($paudPesertas);
    }

    /**
     * @throws FlowException
     */
    public function createNonPtk(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, CreatePesertaNonPtkRequest $request)
    {
        $paudPesertas = $this->service->createPesertaNonPtk($diklat, $kelas, $request->validated());

        return BaseCollection::make($paudPesertas);
    }

    /**
     * @throws FlowException
     */
    public function delete(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        $this->service->validateKelas($diklat, $kelas);
        $this->service->validateKelasBaru($kelas);

        $peserta->delete();

        return true;
    }
}
