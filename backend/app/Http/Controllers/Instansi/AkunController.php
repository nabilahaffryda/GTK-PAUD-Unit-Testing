<?php

namespace App\Http\Controllers\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Requests\Instansi\Admin\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\Akun;
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
     * @param Request $request
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

    /**
     * @param CreateRequest $request
     * @return BaseResource
     * @throws SaveException
     * @throws GuzzleException
     */
    public function create(CreateRequest $request)
    {
        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        return BaseResource::make($paudAdmin);
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     */
    public function fetch(PaudAdmin $paudAdmin)
    {
        if ($paudAdmin->k_group != $this->kGroup) {
            abort(404);
        }

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
        if ($paudAdmin->k_group != $this->kGroup) {
            abort(404);
        }

        return BaseResource::make($this->service->update(instansi(), $paudAdmin, $request->validated()));
    }

    /**
     * @param PaudAdmin $paudAdmin
     * @return BaseResource
     * @throws FlowException
     * @throws SaveException
     */
    public function delete(PaudAdmin $paudAdmin)
    {
        if ($paudAdmin->k_group != $this->kGroup) {
            abort(404);
        }

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
        if ($paudAdmin->k_group != $this->kGroup) {
            abort(404);
        }

        return BaseResource::make($this->service->resetPasword(instansi(), $paudAdmin));
    }

    /**
     * @param $email
     * @return BaseResource
     */
    public function email($email)
    {
        return BaseResource::make(Akun::whereEmail($email)->first());
    }

    /**
     * @return BaseCollection
     */
    public function groups()
    {
        return BaseCollection::make(AkunService::childGroups(akun(), instansi()));
    }
}
