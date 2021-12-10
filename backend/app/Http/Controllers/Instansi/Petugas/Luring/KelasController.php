<?php

namespace App\Http\Controllers\Instansi\Petugas\Luring;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Kelas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudKelasLuring;
use App\Services\Instansi\PetugasKelasLuringService;
use DB;
use Illuminate\Database\Eloquent\Builder;

class KelasController extends Controller
{
    public function __construct(
        protected PetugasKelasLuringService $service,
    )
    {
    }

    public function index()
    {
        return BaseCollection::make($this
            ->service
            ->listKelas(akunId())
            ->with([
                'paudDiklatLuring',
            ])
            ->get()
        );
    }

    public function fetch(PaudKelasLuring $kelas)
    {
        $kelas
            ->load([
                'paudDiklatLuring',
            ])
            ->loadCount('paudKelasPesertaLurings');

        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelasLuring $kelas, IndexRequest $request)
    {
        $pesertas = $kelas
            ->paudKelasPesertaLurings()
            ->when($request->input('keyword'), function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query
                        ->orWhereExists(function ($query) use ($value) {
                            $query
                                ->select(DB::raw(1))
                                ->from('ptk')
                                ->whereColumn('paud_kelas_peserta_luring.ptk_id', 'ptk.ptk_id')
                                ->where('ptk.nama', 'like', "%{$value}%");
                        })
                        ->orWhereExists(function (Builder $query) use ($value) {
                            $query
                                ->select(DB::raw(1))
                                ->from('paud_peserta_nonptk')
                                ->whereColumn('paud_kelas_peserta_luring.paud_peserta_nonptk_id', '=', 'paud_peserta_nonptk.paud_peserta_nonptk_id')
                                ->where('paud_peserta_nonptk.nama', 'like', "%{$value}%");
                        });
                });
            })
            ->with([
                'ptk',
                'PaudPesertaNonptk',
            ]);

        return BaseCollection::make($pesertas
            ->paginate((int)$request->get('count', 10)));
    }
}
