<?php

namespace App\Services;

use App\Exceptions\FlowException;
use App\Models\MGroup;
use App\Models\PaudAkses;
use App\Models\PaudGroupAkses;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Route;

class AksesService
{
    protected $namespaces = [
        'akun' => 'App\\Http\\Controllers\\Instansi',
        'ptk'  => 'App\\Http\\Controllers\\Gtk'
    ];

    protected $whitelist = [
        'facade*',
        '*auth*',
        '*preferensi*',
        'clockwork*',
        'closure',
    ];

    protected $blacklist = [];

    /**
     * @param      $guard
     * @param null $kGroups
     *
     * @return \Illuminate\Database\Query\Builder|MGroup
     */
    public function groups($guard, $kGroups = null)
    {
        return MGroup::query()->whereIn('k_group', app(AkunService::class)->kGroups())
            ->when($kGroups, function ($query) use ($kGroups) {
                $query->whereIn('k_group', (array)$kGroups);
            })
            ->select(['k_group', 'keterangan']);
    }

    /**
     * @param       $guard
     * @param array $aksesIds
     * @param       $keyword
     *
     * @return PaudAkses|Builder
     */
    public function akses($guard, $aksesIds = [], $keyword = null)
    {
        $q = PaudAkses::whereGuard($guard);

        if ($aksesIds) {
            $q->whereIn('paud_akses_id', (array)$aksesIds);
        }

        if ($keyword) {
            $q->where('akses', 'like', "%$keyword%");
        }

        return $q;
    }

    public function groupAkses($kGroups, $aksesIds)
    {
        if (!$kGroups) {
            return [];
        }

        $groupAkseses = PaudGroupAkses::query()->whereIn('k_group', $kGroups)
            ->whereIn('paud_akses_id', $aksesIds)
            ->get();

        $maps = [];
        foreach ($groupAkseses as $item) {
            $maps[$item->k_group][$item->paud_akses_id] = $item->is_aktif;
        }

        return $maps;
    }

    public function fromController($guard, $controller)
    {
        $namespace = $this->namespaces[$guard];

        $controller = str_replace($namespace, '', $controller);
        $controller = str_replace('App\\Http\\Controllers\\', '', $controller);
        $controller = str_replace('Controller@', '.', $controller);
        $controller = str_replace('\\', '', $controller);

        return strtolower(Str::snake($controller, '-'));
    }

    public function fromRequest(Request $request, $guard = 'akun')
    {
        $controller = $request->route()->getActionName();
        return $this->fromController($guard, $controller);
    }

    /**
     * @param string $guard
     * @param PaudAkses[] $akseses
     *
     * @return array
     */
    public function routes($guard, $akseses = [])
    {
        $namespace = $this->namespaces[$guard];

        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $controller = $route->getActionName();
            $key        = $this->fromController($guard, $controller);

            if (!Str::is("$namespace*", $controller)) {
                continue;
            }

            $skip = false;
            foreach ($this->whitelist as $pattern) {
                if (Str::is($pattern, $key)) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            $aksesId = null;
            if (isset($akseses[$key])) {
                $akses   = $akseses[$key];
                $aksesId = $akses->paud_akses_id;
            }

            $routes[$key] = $aksesId;
        }

        // foreach ($akseses as $akses) {
        //     if (!isset($routes[$akses->akses])) {
        //         $routes[$akses->akses] = $akses->paud_akses_id;
        //     }
        // }

        return $routes;
    }

    /**
     * @param $guard
     * @return array
     */
    public function regenerate($guard)
    {
        $akseses = $this->akses($guard)->get()->keyBy('akses');
        $routes  = $this->routes($guard, $akseses);

        // if (!in_array(config('app.env'), ['local', 'devel'])) {
        //     return $routes;
        // }

        foreach ($routes as $key => $id) {

            list($controller, $action) = explode('.', $key);
            $controller = str_replace(['-', '_'], ' ', $controller);

            $action = str_replace('index', '', $action);
            $action = str_replace('fetch', 'detil', $action);
            $action = str_replace('delete', 'hapus', $action);
            $action = str_replace('update', 'edit', $action);
            $action = str_replace('insert', 'buat', $action);

            $labels = implode(' ', array_filter([$action, $controller]));
            $hash   = crc32(implode('.', [$guard, $key]));

            if ($id === null) {
                $id    = $hash;
                $akses = new PaudAkses([
                    'akses'    => $key,
                    'label'    => $labels,
                    'is_aktif' => 0,
                    'guard'    => $guard,
                ]);

                $akses->paud_akses_id = $id;
                $akses->save();

                $routes[$key] = $akses->paud_akses_id;
            }
        }

        return $routes;
    }

    public function getAkses(Collection $groups)
    {
        return PaudAkses::query()
            ->join('paud_group_akses', 'paud_akses.paud_akses_id', '=', 'paud_group_akses.paud_akses_id')
            ->whereIn('paud_group_akses.k_group', $groups->keys())
            ->select('paud_akses.*')
            ->where('paud_akses.is_aktif', 1)
            ->where('paud_group_akses.is_aktif', 1)
            ->distinct()
            ->get();
    }

    public function isAkses(Collection $groups, $PaudAksesId)
    {
        $count = PaudAkses::query()
            ->join('paud_group_akses', 'paud_akses.paud_akses_id', '=', 'paud_group_akses.paud_akses_id')
            ->where('paud_group_akses.paud_akses_id', $PaudAksesId)
            ->whereIn('paud_group_akses.k_group', $groups->keys())
            ->select('paud_akses.*')
            ->where('paud_akses.is_aktif', 1)
            ->where('paud_group_akses.is_aktif', 1)
            ->count();

        return $count ? true : false;
    }

    /**
     * @param Collection|MGroup[] $groups
     * @param Collection|PaudAkses[] $akseses
     * @param array $groupAkses
     *
     * @return bool
     * @throws FlowException
     */
    public function save($groups, $akseses, $groupAkses)
    {
        $groups  = $groups->keyBy(['k_group']);
        $akseses = $akseses->keyBy(['paud_akses_id']);

        // delete existing
        $qDelete = PaudGroupAkses::query();

        if ($groups->count()) {
            $qDelete->whereIn('k_group', $groups->pluck('k_group'));
        }
        if ($akseses->count()) {
            $qDelete->whereIn('paud_akses_id', $akseses->pluck('paud_akses_id'));
        }

        // insert new permission
        $inserts = [];
        if ($groupAkses) {
            foreach ($groupAkses as $kGroup => $items) {
                if (!isset($groups[$kGroup])) {
                    throw new FlowException("Group $kGroup tidak valid");
                }

                foreach ($items as $aksesId => $isAktif) {
                    if (!isset($akseses[$aksesId])) {
                        throw new FlowException("Akses $aksesId tidak valid");
                    }

                    $inserts[] = [
                        'k_group'       => $kGroup,
                        'paud_akses_id' => $aksesId,
                        'is_aktif'      => $isAktif ? 1 : 0,
                    ];
                }
            }
        }

        $qDelete->delete();
        PaudGroupAkses::query()->insert($inserts);

        return true;
    }

    public function saveAktif($aksesIds, $isAktif)
    {
        return PaudAkses::query()->whereIn('paud_akses_id', (array)$aksesIds)
            ->update(['is_aktif' => $isAktif ? 1 : 0]);
    }
}
