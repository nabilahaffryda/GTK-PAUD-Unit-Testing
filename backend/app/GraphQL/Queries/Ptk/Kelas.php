<?php

namespace App\GraphQL\Queries\Ptk;

use App\Models\MKonfirmasiPaud;
use App\Models\PaudKelas;
use App\Models\PaudKelasPeserta;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Kelas
{
    public function list($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return PaudKelas::query()
            ->whereHas('paudKelasPesertas', function ($query) {
                $query->where([
                    'ptk_id'            => ptkId(),
                    'k_konfirmasi_paud' => MKonfirmasiPaud::BERSEDIA,
                ]);
            })
            ->when($args['keyword'] ?? null, function ($query, $value) {
                $query->where('nama', 'like', '%' . $value . '%');
            });
    }

    public function fetch($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->list($rootValue, $args, $context, $resolveInfo)
            ->where('paud_kelas_id', '=', $args['id'])
            ->first();
    }
}