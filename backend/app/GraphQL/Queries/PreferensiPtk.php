<?php

namespace App\GraphQL\Queries;

use App\Models\PaudKelasPeserta;
use App\Services\AkunService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PreferensiPtk
{
    public function __construct(
        protected AkunService $akunService,
    )
    {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
    {
        return [
            'konfirmasiKesediaan' => function () {
                return $this->konfirmasiKesediaan();
            },
            'konfig'               => [
                'simpkb' => config('simpkb.url'),
            ],
        ];
    }

    protected function konfirmasiKesediaan()
    {
        return PaudKelasPeserta::where([
            'tahun'    => config('paud.tahun'),
            'angkatan' => config('paud.angkatan'),
            'ptk_id'   => ptkId(),
        ])->exists();
    }
}
