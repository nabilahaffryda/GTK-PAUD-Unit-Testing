<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Requests\Instansi\Admin\Pengajar\SetPengajarRequest;
use App\Http\Requests\Instansi\Admin\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Models\MPetugasPaud;
use App\Models\PaudAdmin;
use App\Services\Instansi\PetugasService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent;
use Illuminate\Http\Request;

class PengajarController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_DIKLAT_PAUD;

    /**
     * @return BaseCollection
     */
    public function index(Request $request)
    {
        $params = array_merge($request->input('filter', []), [
            'k_group' => [$this->kGroup, MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD],
        ]);

        return BaseCollection::make($this
            ->service
            ->query(instansi(), $params)
            ->leftJoin('paud_petugas', function ($query) {
                $query
                    ->whereColumn('paud_petugas.akun_id', 'paud_admin.akun_id')
                    ->whereColumn('paud_petugas.tahun', 'paud_admin.tahun')
                    ->whereColumn('paud_petugas.angkatan', 'paud_admin.angkatan')
                    ->whereIn('paud_petugas.k_petugas_paud', [
                        MPetugasPaud::PENGAJAR,
                        MPetugasPaud::PENGAJAR_TAMBAHAN,
                    ]);
            })
            ->select(['paud_admin.*', 'akun_instansi.token', 'paud_petugas.is_inti'])
            ->when($request->has('is_inti'), function (Eloquent\Builder $query) use ($request) {
                $query->where('paud_petugas.is_inti', $request->input('is_inti'));
            })
            ->when($request->has('is_refreshment'), function (Eloquent\Builder $query) use ($request) {
                $query->where('paud_petugas.is_refreshment', $request->input('is_refreshment'));
            })
            ->orderBy('akun.nama')
            ->paginate((int)$request->get('count', 10))
            ->format(function (PaudAdmin $item) {
                return $item
                    ->akun
                    ->makeVisible(['passwd']);
            }));
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     */
    public function fetch(PaudAdmin $paudAdmin)
    {
        if (!in_array($paudAdmin->k_group, [$this->kGroup, MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD])) {
            throw new FlowException("Grup tidak dikenali");
        }

        return BaseResource::make($this->service->fetch(instansi(), $paudAdmin));
    }

    /**
     * @throws SaveException
     * @throws FlowException
     * @throws GuzzleException
     */
    public function create(CreateRequest $request)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        app(PetugasService::class)->create($paudAdmin, [
            'k_petugas_paud' => MPetugasPaud::PENGAJAR,
        ]);
        return BaseResource::make($paudAdmin);
    }

    public function update(UpdateRequest $request, PaudAdmin $paudAdmin)
    {
        throw new FlowException('Data akun bisa diubah secara mandiri melalui login Akun yang bersangkutan');
    }

    public function delete(PaudAdmin $paudAdmin)
    {
        app(PetugasService::class)->delete($paudAdmin);
        parent::delete($paudAdmin);
    }

    /**
     * @throws FlowException
     */
    public function setStatus(SetPengajarRequest $request)
    {
        return BaseCollection::make(app(PetugasService::class)->setStatusAkun([[$this->kGroup, MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD]], $request->akun_ids, [
            'is_inti'        => $request->is_inti,
            'is_refreshment' => $request->is_bimtek,
        ]));
    }

    /**
     * @throws FlowException
     */
    public function resetStatus(PaudAdmin $paudAdmin)
    {
        $petugas = app(PetugasService::class)->getPetugas($paudAdmin->akun, [$this->kGroup, MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD]);
        if (!$petugas) {
            throw new FlowException('Data akun bukan merupakan pengajar');
        }

        return BaseResource::make(app(PetugasService::class)->resetStatus($petugas, ['is_inti', 'is_refreshment']));
    }
}
