<?php

namespace App\Http\Controllers\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Requests\Instansi\Admin\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Models\MJenisInstansi;
use App\Models\PaudAdmin;
use App\Services\AkunService;
use App\Services\Instansi\AdminService;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    protected $kGroup;

    public function __construct(
        protected AdminService $service,
    )
    {
    }

    /**
     * @throws FlowException
     */
    protected function validateGroup(PaudAdmin $paudAdmin = null)
    {
        if (instansi()->k_jenis_instansi == MJenisInstansi::PAUD && $this->kGroup == MGroup::AP_LPD_DIKLAT_PAUD) {
            return;
        }

        $kGroups = AkunService::childGroups(akun(), instansi())
            ->pluck('k_group')
            ->all();

        if (!in_array($this->kGroup, $kGroups)) {
            throw new FlowException("Grup tidak dikenali");
        }

        if ($paudAdmin && $paudAdmin->k_group != $this->kGroup) {
            throw new FlowException("Grup tidak dikenali");
        }
    }

    /**
     * @param Request $request
     * @return BaseCollection
     */
    public function index(Request $request)
    {
        $params = array_merge($request->input('filter', []), [
            'k_group' => $this->kGroup,
            'keyword' => $request->input('keyword'),
        ]);

        return BaseCollection::make($this
            ->service
            ->query(instansi(), $params)
            ->select(['paud_admin.*', 'akun_instansi.token'])
            ->paginate((int)$request->get('count', 10))
            ->format(function (PaudAdmin $item) {
                return $item
                    ->akun
                    ->makeVisible(['passwd']);
            }));
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator|null
     * @throws IOException
     * @throws WriterNotOpenedException
     */
    public function download(Request $request)
    {
        $params = array_merge($request->input('filter', []), [
            'k_group' => $this->kGroup,
        ]);

        return $this->service->download(instansi(), $params);
    }


    public function downloadAktivasi(Request $request)
    {
        $params = array_merge($request->input('filter', []), [
            'k_group' => $this->kGroup,
        ]);

        return $this->service->downloadAktivasi(instansi(), $params);
    }

    /**
     * @param CreateRequest $request
     * @return BaseResource
     * @throws SaveException
     * @throws GuzzleException
     * @throws FlowException
     */
    public function create(CreateRequest $request)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        return BaseResource::make($paudAdmin);
    }

    public function template()
    {
        return response()->file(resource_path('xlsx/akun-template.xlsx'));
    }

    public function upload(Request $request)
    {
        return $this->service->upload(akun(), instansi(), $request->file('file'), $this->kGroup);
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     */
    public function fetch(PaudAdmin $paudAdmin)
    {
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->fetch(instansi(), $paudAdmin));
    }

    /**
     * @param UpdateRequest $request
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws SaveException
     * @throws FlowException
     */
    public function update(UpdateRequest $request, PaudAdmin $paudAdmin)
    {
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->update(instansi(), $paudAdmin, $request->validated()));
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     */
    public function aktif(PaudAdmin $paudAdmin)
    {
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->setAktif($paudAdmin, true));
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     */
    public function nonAktif(PaudAdmin $paudAdmin)
    {
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->setAktif($paudAdmin, false));
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     * @throws SaveException
     */
    public function delete(PaudAdmin $paudAdmin)
    {
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->delete(instansi(), $paudAdmin));
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     * @throws GuzzleException
     * @throws SaveException
     */
    public function reset(PaudAdmin $paudAdmin)
    {
        $this->validateGroup($paudAdmin);

        return BaseResource::make($this->service->resetPasword(instansi(), $paudAdmin));
    }

    /**
     * @param $email
     * @return BaseResource
     */
    public function email($email)
    {
        return BaseResource::make($this->service->findEmail($email));
    }

    /**
     * @return BaseCollection
     */
    public function groups()
    {
        return BaseCollection::make(AkunService::childGroups(akun(), instansi()));
    }
}
