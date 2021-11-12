<?php

namespace App\Http\Controllers\Instansi\Lpd\Luring;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\IndexRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\UploadRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\PaudDiklatLuring;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPetugasLuring;
use App\Services\Instansi\KelasLuringService;
use Illuminate\Pagination\LengthAwarePaginator;

class KelasController extends Controller
{
    public function __construct(
        protected KelasLuringService $service,
    )
    {
    }

    public function index(PaudDiklatLuring $diklat, IndexRequest $request)
    {
        /** @var LengthAwarePaginator|PaudKelasLuring[] $kelases */
        $kelases = $this->service->index($diklat, $request->validated())
            ->paginate((int)$request->get('count', 10));

        $kelasIds = $kelases->getCollection()->pluck('paud_kelas_luring_id');

        $petugasKelases = PaudKelasPetugasLuring::query()
            ->whereIn('paud_kelas_luring_id', $kelasIds)
            ->whereIn('k_petugas_paud', [MPetugasPaud::PENGAJAR, MPetugasPaud::PENGAJAR_TAMBAHAN, MPetugasPaud::PEMBIMBING_PRAKTIK])
            ->groupBy('paud_kelas_luring_id', 'k_konfirmasi_paud')
            ->get(['paud_kelas_luring_id', 'k_konfirmasi_paud', \DB::raw('COUNT(1) jumlah')])
            ->groupBy('paud_kelas_luring_id');

        foreach ($kelases as $kelas) {
            $kelas->is_siap_ajuan = false;

            if ($petugases = $petugasKelases->get($kelas->paud_kelas_luring_id)) {
                $kelas->is_siap_ajuan = true;
                foreach ($petugases as $petugas) {
                    if ($petugas->k_konfirmasi_paud != MKonfirmasiPaud::BERSEDIA) {
                        $kelas->is_siap_ajuan = false;
                        continue 2;
                    }
                }
            }
        }

        return BaseCollection::make($kelases);
    }

    /**
     * @throws SaveException
     */
    public function create(PaudDiklatLuring $diklat, CreateRequest $request)
    {
        $paudKelas = $this->service->create($diklat, $request->validated());

        return BaseResource::make($paudKelas);
    }

    /**
     * @throws FlowException
     */
    public function fetch(PaudDiklatLuring $diklat, PaudKelasLuring $kelas)
    {
        return BaseResource::make($this->service->fetch($diklat, $kelas));
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function update(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, CreateRequest $request)
    {
        $paudKelas = $this->service->update($diklat, $kelas, $request->validated());

        return BaseResource::make($paudKelas);
    }

    /**
     * @throws FlowException
     */
    public function uploadJadwal(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, UploadRequest $request)
    {
        $this->service->uploadJadwal($diklat, $kelas, $request->file);
        return BaseResource::make($kelas);
    }
}
