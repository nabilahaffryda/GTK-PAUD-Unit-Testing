<?php

namespace App\GraphQL\Queries;

use App\Models\Instansi;
use App\Services\AksesService;
use App\Services\AkunService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Preferensi
{
    protected Instansi $instansi;
    /**
     * @var Collection
     */
    protected $akunInstansi;
    protected $groups;

    public function __construct(
        protected AkunService $akunService,
    )
    {
    }

    /**
     * @throws AuthorizationException
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
    {
        $this->instansi = instansi();

        return [
            'groups'   => function () {
                return $this->groups();
            },
            'akseses'  => function () {
                return app(AksesService::class)->getAkses($this->groups()->keyBy('k_group'));
            },
            'aktivasi' => function () {
                return $this->akunService->isAktivasi($this->akunInstansi());
            },
            'konfig'   => [
                'simpkb' => config('simpkb.url'),
            ],
        ];
    }

    protected function akunInstansi()
    {
        if (!$this->akunInstansi) {
            $this->akunInstansi = $this->akunService->akunInstansis($this->instansi);
        }

        return $this->akunInstansi;
    }

    protected function groups()
    {
        if (!$this->groups) {
            $this->groups = $this->akunInstansi()
                ->load('mGroup')
                ->pluck('mGroup');
        }

        return $this->groups;
    }
}
