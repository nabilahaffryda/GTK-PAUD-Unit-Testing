<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Requests\Instansi\Admin\PengajarTambahanRequest;
use App\Http\Requests\Instansi\Admin\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Models\MPetugasPaud;
use App\Models\PaudAdmin;
use App\Services\Instansi\PetugasService;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Query;
use Illuminate\Http\Request;

class PengajarTambahanController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD;

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
            ->select(['paud_admin.*', 'akun_instansi.token'])
            ->when($request->input('k_unsur_pengajar_paud'), function (Eloquent\Builder $query, $value) {
                $query
                    ->whereExists(function (Query\Builder $query) use ($value) {
                        $query
                            ->select(DB::raw(1))
                            ->from('paud_petugas')
                            ->whereColumn('paud_petugas.akun_id', 'paud_admin.akun_id')
                            ->whereColumn('paud_petugas.tahun', 'paud_admin.tahun')
                            ->whereColumn('paud_petugas.angkatan', 'paud_admin.angkatan')
                            ->where('paud_petugas.k_petugas_paud', MPetugasPaud::PENGAJAR_TAMBAHAN)
                            ->where('paud_petugas.k_unsur_pengajar_paud', $value);
                    });
            })
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
    public function createPengajar(CreateRequest $request, PengajarTambahanRequest $pengajarTambahanRequest)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        app(PetugasService::class)->create($paudAdmin, MPetugasPaud::PENGAJAR_TAMBAHAN, [
            'k_unsur_pengajar_paud' => $pengajarTambahanRequest->k_unsur_pengajar_paud,
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

    public function uploadPengajarTambahan(Request $request, PengajarTambahanRequest $pengajarTambahanRequest)
    {
        return $this->service->uploadPengajarTambahan(akun(), instansi(), $request->file('file'), $this->kGroup, $pengajarTambahanRequest->k_unsur_pengajar_paud);
    }
}
