<?php

namespace App\GraphQL\Directives;

use App\Services\AkunService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\Access\AuthorizationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class InstansiDirective extends BaseDirective implements FieldMiddleware
{

    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
directive @instansi on FIELD_DEFINITION
GRAPHQL;
    }

    /**
     * Wrap around the final field resolver.
     *
     * @param FieldValue $fieldValue
     * @param Closure $next
     * @return FieldValue
     */
    public function handleField(FieldValue $fieldValue, Closure $next)
    {
        $resolver = $fieldValue->getResolver();

        $fieldValue->setResolver(function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
            $instansiId = $args['instansi_id'] ?? null;
            if (!$instansiId) {
                throw new \Nuwave\Lighthouse\Exceptions\AuthorizationException('Instansi tidak ditemukan/dikenali');
            }

            try {
                app(AkunService::class)->validateInstansi($instansiId);
            } catch (AuthorizationException $e) {
                throw new \Nuwave\Lighthouse\Exceptions\AuthorizationException($e->getMessage());
            }

            return $resolver($root, $args, $context, $resolveInfo);
        });

        return $next($fieldValue);
    }
}
