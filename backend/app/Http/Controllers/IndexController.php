<?php

namespace App\Http\Controllers;

use App\Models\AkunInstansi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $response = [
            'data' => [
                'domain' => $request->getHost(),
                'time'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        $akun = akun();
        if (akun()) {
            $akunInstansi = AkunInstansi::query()
                ->where('akun_id', $akun->akun_id)
                ->orderBy('instansi_id')
                ->first();

            $response['data']['akun'] = $akun->toArray();
            $response['data']['akun']['akun_instansi'] = $akunInstansi->toArray();
        }

        if (ptk()) {
            $response['data']['ptk'] = ptk();
        }

        return response()->json($response);
    }
}
