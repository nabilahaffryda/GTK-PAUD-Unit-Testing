<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterRequest;
use App\Http\Resources\BaseCollection;
use App\Models\MGroup;
use App\Models\PaudInstansi;
use App\Remotes\SimpkbAkun;
use App\Services\AksesService;
use App\Services\AkunService;
use App\Services\Instansi\PetugasKelasService;
use App\Services\MasterService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function preferensi(AkunService $akunService, AksesService $aksesService)
    {
        $akun     = akun();
        $instansi = instansi();
        $simpkb   = config('simpkb.url');

        $akunInstansi = $akunService->akunInstansis($instansi);
        $groups       = $akunService->getGroups($akunInstansi);
        $akses        = $aksesService->getAkses($groups)->pluck('paud_akses_id', 'akses');

        $aktivasi = $akunService->isAktivasi($akunInstansi);

        $konfirmasi = false;

        $paudInstansi = PaudInstansi::where([
            'instansi_id' => $instansi->instansi_id,
            'tahun'       => config('paud.tahun'),
            'angkatan'    => config('paud.angkatan'),
        ])->first();

        $subgroup = $groups->intersectByKeys(array_flip([MGroup::PENGAJAR_DIKLAT_PAUD, MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD, MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD]));
        if ($subgroup->isNotEmpty()) {
            $konfirmasi = app(PetugasKelasService::class)
                ->listKonfirmasiKesediaan(akunId())
                ->exists();
        }

        return [
            'akun'     => $akun,
            'instansi' => $instansi,
            'groups'   => $groups,
            'akses'    => $akses,

            'paud_instansi' => $paudInstansi,

            'aktivasi' => $aktivasi,
            'konfig'   => [
                'simpkb' => $simpkb,
            ],

            'konfirmasi_kesediaan' => $konfirmasi,
        ];
    }

    public function master(MasterRequest $request, MasterService $masterService)
    {
        return response()->json([
            'data' => $masterService->fetch($request->name, $request->column ?? [], $request->filter ?? []),
        ]);
    }

    public function instansi(Request $request, AkunService $akunService)
    {
        return BaseCollection::make($akunService->queryInstansi(akun(), $request->all())->paginate((int)$request->get('count', 100)));
    }

    public function layanans(Request $request)
    {
        return app(SimpkbAkun::class)->layanans(akun()->paspor_id);
    }
}
