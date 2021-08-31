<?php

namespace App\Http\Controllers\Instansi\Petugas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Kelas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudKelas;
use App\Services\Instansi\PetugasKelasService;
use DB;

class KelasController extends Controller
{
    public function __construct(
        protected PetugasKelasService $service,
    )
    {
    }

    public function index()
    {
        return BaseCollection::make($this
            ->service
            ->listKelas(akunId())
            ->with([
                'paudDiklat.paudPeriode',
            ])
            ->get()
        );
    }

    public function fetch(PaudKelas $kelas)
    {
        $kelas
            ->load([
                'paudDiklat.paudPeriode',
            ])
            ->loadCount('paudKelasPesertas');

        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelas $kelas, IndexRequest $request)
    {
        $pesertas = $kelas
            ->paudKelasPesertas()
            ->when($request->input('keyword'), function ($query, $value) {
                $query->whereExists(function ($query) use ($value) {
                    $query
                        ->select(DB::raw(1))
                        ->from('ptk')
                        ->whereColumn('paud_kelas_peserta.ptk_id', 'ptk.ptk_id')
                        ->where('ptk.nama', 'like', "%{$value}%");
                });
            })
            ->with(['ptk']);

        return BaseCollection::make($pesertas
            ->paginate((int)$request->get('count', 10)));
    }
}
