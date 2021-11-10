<?php

namespace App\Http\Controllers\Instansi\Lpd\Peserta;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Peserta\NonPtk\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Peserta\NonPtk\IndexRequest;
use App\Http\Requests\Instansi\Lpd\Peserta\NonPtk\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Models\PaudPesertaNonptk;
use App\Services\Instansi\Peserta\NonPtkService;
use App\Services\InstansiService;
use Symfony\Component\HttpFoundation\Response;

class NonPtkController extends Controller
{
    protected ?PaudInstansi $instansi = null;

    public function __construct(
        protected NonPtkService $service,
    )
    {
    }

    protected function getInstansi()
    {
        if (!$this->instansi) {
            $this->instansi = app(InstansiService::class)->getPaudInstansi(instansi());
        }

        return $this->instansi;
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make(
            $this->service
                ->index($this->getInstansi(), $request->validated())
                ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function create(CreateRequest $request)
    {
        return BaseResource::make(
            $this->service
                ->create($this->getInstansi(), $request->validated())
        );
    }

    public function fetch(PaudPesertaNonptk $peserta)
    {
        if ($peserta->paud_instansi_id != $this->getInstansi()->paud_instansi_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return BaseResource::make(
            $peserta->load([
                'mDiklatPaud',
                'mJenjangDiklatPaud',
            ])
        );
    }

    /**
     * @throws FlowException
     */
    public function update(PaudPesertaNonptk $peserta, UpdateRequest $request)
    {
        if ($peserta->paud_instansi_id != $this->getInstansi()->paud_instansi_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return BaseResource::make(
            $this->service
                ->update($this->getInstansi(), $peserta, $request->validated())
        );
    }

    /**
     * @throws FlowException
     */
    public function delete(PaudPesertaNonptk $peserta)
    {
        if ($peserta->paud_instansi_id != $this->getInstansi()->paud_instansi_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return BaseResource::make(
            $this->service
                ->delete($this->getInstansi(), $peserta)
        );
    }
}
