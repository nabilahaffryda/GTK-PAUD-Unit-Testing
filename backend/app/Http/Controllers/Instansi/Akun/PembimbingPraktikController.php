<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Requests\Instansi\Admin\PembimbingPraktik\SetIntiRequest;
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

class PembimbingPraktikController extends AkunController
{
    protected $kGroup = MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD;

    /**
     * @return BaseCollection
     */
    public function index(Request $request)
    {
        $params = array_merge($request->input('filter', []), [
            'k_group' => $this->kGroup,
        ]);

        return BaseCollection::make($this
            ->service
            ->query(instansi(), $params)
            ->leftJoin('paud_petugas', function ($query) {
                $query
                    ->whereColumn('paud_petugas.akun_id', 'paud_admin.akun_id')
                    ->whereColumn('paud_petugas.tahun', 'paud_admin.tahun')
                    ->whereColumn('paud_petugas.angkatan', 'paud_admin.angkatan')
                    ->where('paud_petugas.k_petugas_paud', MPetugasPaud::PEMBIMBING_PRAKTIK);
            })
            ->select(['paud_admin.*', 'akun_instansi.token', 'paud_petugas.is_inti'])
            ->when(isset($params['is_inti']), function (Eloquent\Builder $query) use ($params) {
                $query->where('paud_petugas.is_inti', $params['is_inti']);
            })
            ->orderBy('paud_admin_id', 'desc')
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
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->fetchPengajar(instansi(), $paudAdmin));
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
            'k_petugas_paud' => MPetugasPaud::PEMBIMBING_PRAKTIK,
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
    public function setInti(SetIntiRequest $request)
    {
        return BaseCollection::make(app(PetugasService::class)->setStatusAkun([MPetugasPaud::PEMBIMBING_PRAKTIK], $request->akun_ids, ['is_inti' => 1]));
    }

    /**
     * @throws FlowException
     */
    public function resetInti(PaudAdmin $paudAdmin)
    {
        $petugas = app(PetugasService::class)->getPetugas($paudAdmin->akun, MPetugasPaud::PEMBIMBING_PRAKTIK);
        if (!$petugas) {
            throw new FlowException('Data akun bukan merupakan pembimbing praktik');
        }

        return BaseResource::make(app(PetugasService::class)->resetStatus($petugas, ['is_inti' => true]));
    }
}
