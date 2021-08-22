<?php

namespace App\GraphQL\Queries;

use App\Services\AkunService;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class InstansisAkun
{
    /**
     * @param $rootValue
     * @param array<string, mixed> $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Builder
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $params = ['filter' => ['keyword' => $args['keyword'] ?? null]];

        return app(AkunService::class)
            ->queryInstansi(akun(), $params);
    }
}
