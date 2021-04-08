<?php

namespace App\Http\Controllers;

use App\Services\AkunService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $response = [
            'domain' => $request->getHost(),
            'time'   => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $akun = akun();
        if (akun()) {
            $akunInstansi = app(AkunService::class)->akunInstansi($akun);

            $response['akun']     = $akun;
            $response['instansi'] = $akunInstansi->instansi;
            $response['group']    = $akunInstansi->mGroup;
        }

        if (ptk()) {
            $response['ptk'] = ptk();
        }

        return response()->json([
            'data' => $response,
        ]);
    }
}
