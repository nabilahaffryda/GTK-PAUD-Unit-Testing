<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Kelas\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Kelas\IndexRequest;
use App\Http\Requests\Instansi\Lpd\Kelas\UploadRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Models\PaudKelasPeserta;
use App\Models\PaudKelasPetugas;
use App\Services\Instansi\KelasService;
use Illuminate\Pagination\LengthAwarePaginator;

class KelasController extends Controller
{
    public function __construct(protected KelasService $service)
    {
    }

    public function index(PaudDiklat $paudDiklat, IndexRequest $request)
    {
        /** @var LengthAwarePaginator|PaudKelas[] $kelases */
        $kelases = $this->service->index($paudDiklat, $request->validated())
            ->paginate((int)$request->get('count', 10));

        $kelasIds = $kelases->getCollection()->pluck('paud_kelas_id');

        $petugasKelases = PaudKelasPetugas::query()
            ->whereIn('paud_kelas_id', $kelasIds)
            ->whereIn('k_petugas_paud', [MPetugasPaud::PENGAJAR, MPetugasPaud::PENGAJAR_TAMBAHAN, MPetugasPaud::PEMBIMBING_PRAKTIK])
            ->groupBy('paud_kelas_id', 'k_konfirmasi_paud')
            ->get(['paud_kelas_id', 'k_konfirmasi_paud', \DB::raw('COUNT(1) jumlah')])
            ->groupBy('paud_kelas_id');

        $pesertaKelases = PaudKelasPeserta::query()
            ->whereIn('paud_kelas_id', $kelasIds)
            ->groupBy('paud_kelas_id', 'k_konfirmasi_paud')
            ->get(['paud_kelas_id', 'k_konfirmasi_paud', \DB::raw('COUNT(1) jumlah')])
            ->groupBy('paud_kelas_id');

        foreach ($kelases as $kelas) {
            $kelas->is_siap_ajuan = false;

            if ($petugases = $petugasKelases->get($kelas->paud_kelas_id)) {
                $kelas->is_siap_ajuan = true;
                foreach ($petugases as $petugas) {
                    if ($petugas->k_konfirmasi_paud != MKonfirmasiPaud::BERSEDIA) {
                        $kelas->is_siap_ajuan = false;
                        continue 2;
                    }
                }
            }

            if ($pesertas = $pesertaKelases->get($kelas->paud_kelas_id)) {
                $kelas->is_siap_ajuan = true;
                foreach ($pesertas as $peserta) {
                    if ($peserta->k_konfirmasi_paud != MKonfirmasiPaud::BERSEDIA) {
                        $kelas->is_siap_ajuan = false;
                        continue 2;
                    }
                }
            }
        }

        return BaseCollection::make($kelases);
    }

    public function create(PaudDiklat $paudDiklat, CreateRequest $request)
    {
        $paudKelas = $this->service->create($paudDiklat, $request->validated());

        return BaseResource::make($paudKelas);
    }

    public function fetch(PaudDiklat $paudDiklat, PaudKelas $kelas)
    {
        return BaseResource::make($this->service->fetch($paudDiklat, $kelas));
    }

    public function update(PaudDiklat $paudDiklat, PaudKelas $kelas, CreateRequest $request)
    {
        $paudKelas = $this->service->update($paudDiklat, $kelas, $request->validated());

        return BaseResource::make($paudKelas);
    }

    /**
     * @throws FlowException
     */
    public function uploadJadwal(PaudDiklat $paudDiklat, PaudKelas $kelas, UploadRequest $request)
    {
        $this->service->uploadJadwal($paudDiklat, $kelas, $request->file);
        return BaseResource::make($kelas);
    }
}
