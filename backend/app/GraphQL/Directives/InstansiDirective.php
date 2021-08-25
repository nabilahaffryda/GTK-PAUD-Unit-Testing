<?php

namespace App\GraphQL\Directives;

use App\Models\Instansi;
use App\Services\AkunService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class InstansiDirective extends BaseDirective implements FieldMiddleware
{

    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
directive @instansi on FIELD_DEFINITION | OBJECT
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
                throw new AuthorizationException('Instansi tidak ditemukan/dikenali');
            }

            $instansi = Instansi::find($instansiId);
            if (!$instansi) {
                throw new AuthorizationException('Instansi tidak ditemukan/dikenali');
            }

            $akunInstansi = app(AkunService::class)->akunInstansis($instansi)->first();
            if (!$akunInstansi) {
                throw new AuthorizationException("Instansi {$instansiId} tidak dikenali");
            }

            app()->instance('INSTANSI', $instansi);

            return $resolver($root, $args, $context, $resolveInfo);
        });

        return $next($fieldValue);
    }
}
