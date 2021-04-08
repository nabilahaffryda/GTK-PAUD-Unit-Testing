<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterRequest;
use App\Services\MasterService;

class IndexController extends Controller
{
    public function preferensi()
    {
        return [
            ''
        ];
    }

    public function master(MasterRequest $request, MasterService $masterService)
    {
        return response()->json([
            'data' => $masterService->fetch($request->name, $request->column ?? [], $request->filter ?? []),
        ]);
    }
}
