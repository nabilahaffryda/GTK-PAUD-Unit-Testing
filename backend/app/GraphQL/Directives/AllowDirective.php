<?php

namespace App\GraphQL\Directives;

use App\Services\AksesService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AllowDirective extends BaseDirective implements FieldMiddleware
{

    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
directive @allow(ability: String!) on FIELD_DEFINITION | OBJECT
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
        $ability  = $this->directiveArgValue('ability');

        $fieldValue->setResolver(function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver, $ability) {
            $abilities = explode('|', $ability);
            try {
                app(AksesService::class)->validate($abilities);
            } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
                throw new AuthorizationException($e->getMessage());
            }

            return $resolver($root, $args, $context, $resolveInfo);
        });

        return $next($fieldValue);
    }
}
