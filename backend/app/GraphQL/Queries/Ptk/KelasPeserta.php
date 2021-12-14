<?php

namespace App\GraphQL\Queries\Ptk;

use App\Models\PaudKelasPeserta;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class KelasPeserta
{
    public function list($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return PaudKelasPeserta::query()
            ->where('ptk_id', '=', ptkId())
            ->when($args['keyword'] ?? null, function ($query, $value) {
                $query->whereHas('paudKelas', function ($query) use ($value) {
                    $query->where('nama', 'like', '%' . $value . '%');
                });
            });
    }

    public function fetch($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->list($rootValue, $args, $context, $resolveInfo)
            ->where('paud_kelas_peserta_id', '=', $args['id'])
            ->first();
    }

    public function fetchByKelas($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->list($rootValue, $args, $context, $resolveInfo)
            ->where('paud_kelas_id', '=', $args['kelasId'])
            ->first();
    }
}
