<?php

namespace App\GraphQL\Queries;

use App\Services\MasterService;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Master
{
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): array
    {
        $table = [$args['table']];

        $column = [];
        if ($val = $args['column'] ?? null) {
            $column[] = [$val];
        }

        $filter = [];
        foreach ($args['filter'] ?? [] as $arg) {
            $key = $arg['column'];

            if (isset($arg['operator']) && $arg['operator'] != 'In') {
                $op    = $arg['operator'];
                $value = $arg['value'][0] ?? null;
            } else {
                $op    = 'in';
                $value = $arg['value'];
            }

            $filter = [
                $key => [$op, $value],
            ];
        }
        $filter = [$filter];

        $res = app(MasterService::class)->fetch($table, $column, $filter);

        $result = [];
        foreach ($res[0] as $key => $value) {
            $result[] = [
                'key'   => $key,
                'value' => $value,
            ];
        }

        return $result;
    }
}
