<?php

namespace App\Http\Controllers;

use App\Exceptions\FlowException;
use App\Services\AksesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AksesController extends Controller
{
    protected $service;

    public function __construct(AksesService $aksesService)
    {
        $this->service = $aksesService;

        $this->middleware(function ($request, $next) {
            if (!akun() || !Str::is("*@jayantara.co.id", akun()->email)) {
                throw new FlowException("Pengaturan Akses hanya untuk Super Admin");
            }
            return $next($request);
        });
    }

    public function groups(Request $request)
    {
        $guard = $request->get('guard', 'web');

        return [
            'groups' => $this->service->groups($guard)->get(),
        ];
    }

    public function index(Request $request)
    {
        $guard    = $request->get('guard', config('auth.defaults.guard'));
        $kGroups  = $request->get('k_group');
        $aksesId  = $request->get('akses_id');
        $aksesKey = $request->get('akses_key');

        $this->service->regenerate($guard);

        $groups     = $this->service->groups($guard, $kGroups)->get();
        $akseses    = $this->service->akses($guard, $aksesId, $aksesKey)->get();
        $groupAkses = $this->service->groupAkses($groups->pluck('k_group'), $akseses->pluck('psp_akses_id'));

        return [
            'groups'      => $groups,
            'akseses'     => $akseses,
            'group_akses' => $groupAkses,
        ];
    }

    public function save(Request $request)
    {
        $guard      = $request->get('guard', 'web');
        $kGroups    = $request->get('k_group');
        $aksesId    = $request->get('akses_id');
        $aksesKey   = $request->get('akses_key');
        $groupAkses = $request->get('group_akses');

        $groups  = $this->service->groups($guard, $kGroups)->get();
        $akseses = $this->service->akses($guard, $aksesId, $aksesKey)->get();
        $success = $this->service->save($groups, $akseses, $groupAkses);

        return ['status' => $success];
    }

    public function saveAktif(Request $request)
    {
        $aksesId = $request->get('akses_id');
        $isAktif = $request->get('is_aktif');

        $success = $this->service->saveAktif($aksesId, $isAktif);

        return ['status' => $success];
    }
}
