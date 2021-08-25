<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Kelas\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Kelas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MKonfirmasiPaud;
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

        $belumKonfirms = PaudKelasPetugas::query()
            ->whereIn('paud_kelas_id', $kelasIds)
            ->groupBy('paud_kelas_id', 'k_konfirmasi_paud')
            ->get(['paud_kelas_id', 'k_konfirmasi_paud', \DB::raw('COUNT(1) jumlah')])
            ->groupBy('paud_kelas_id');

        $pesertas = PaudKelasPeserta::query()
            ->whereIn('paud_kelas_id', $kelasIds)
            ->groupBy('paud_kelas_id')
            ->get(['paud_kelas_id', \DB::raw('COUNT(1) jumlah')])
            ->pluck('jumlah', 'paud_kelas_id');

        foreach ($kelases as $kelas) {
            $kelas->is_siap_ajuan = true;

            if ($data = $belumKonfirms->get($kelas->paud_kelas_id)) {
                foreach ($data as $item) {
                    switch ($item->k_konfirmasi_paud) {
                        case MKonfirmasiPaud::BERSEDIA:
                            // TODO: cek komposisi petugas apakah boleh diajukan atau tidak
                            break;

                        case MKonfirmasiPaud::DIHAPUS:
                            break;

                        default:
                            if ($item->jumlah > 0) {
                                $kelas->is_siap_ajuan = false;
                                continue 3;
                            }
                    }
                }
            }

            if ($pesertas->get($kelas->paud_kelas_id, 0) <= 0) {
                $kelas->is_siap_ajuan = false;
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
}
