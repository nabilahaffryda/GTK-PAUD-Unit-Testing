<?php

namespace App\Http\Controllers\Instansi\Lpd\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Kelas\CreatePeserta;
use App\Http\Requests\Instansi\Lpd\Kelas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Models\PaudKelasPeserta;
use App\Models\Ptk;
use App\Services\Instansi\KelasService;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function __construct(protected KelasService $service)
    {
    }

    public function index(PaudDiklat $paudDiklat, PaudKelas $kelas, IndexRequest $request)
    {
        return BaseCollection::make($this->service->indexPeserta($kelas, $request->validated())->get());
    }

    public function download(PaudKelas $kelas)
    {

    }

    public function delete(PaudDiklat $paudDiklat, PaudKelas $kelas, PaudKelasPeserta $peserta)
    {
        if ($peserta->paud_kelas_id <> $kelas->paud_kelas_id) {
            throw new FlowException("Peserta tidak ditemukan");
        }

        $peserta->delete();

        return true;
    }
}
