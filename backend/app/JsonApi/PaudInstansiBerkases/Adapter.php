<?php

namespace App\JsonApi\PaudInstansiBerkas;

use App\Models\PaudInstansiBerkas;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    /**
     * Mapping of JSON API attribute field names to model keys.
     *
     * By default, JSON API attribute fields will automatically be converted to the
     * underscored or camel cased equivalent for the model key. E.g. if the model
     * uses snake case, the JSON API field `published-at` will be converted
     * to `published_at`. If the model does not use snake case, it will be converted
     * to `publishedAt`.
     *
     * For any fields that do not directly convert to model keys, you can list them
     * here. For example, if the JSON API field `published-at` needed to map to the
     * `published_date` model key, then it can be listed as follows:
     *
     * ```php
     * protected $attributes = [
     *   'published-at' => 'published_date',
     * ];
     * ```
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Mapping of filter keys to model query scopes.
     *
     * The `filterWithScopes` method will map JSON API filters to
     * model scopes, and pass the filter value to that scope.
     * For example, if the client has sent a `filter[slug]` query
     * parameter, we expect either there to be a `scopeSlug` method
     * on the model, or we will use Eloquent's magic `whereSlug` method.
     *
     * If you need to map a filter parameter to a different scope name,
     * then you can define it here. For example if `filter[slug]`
     * needed to be passed to the `onlySlug` scope, it can be defined
     * as follows:
     *
     * ```php
     * protected $filterScopes = [
     *      'slug' => 'onlySlug'
     * ];
     * ```
     *
     * If you want a filter parameter to not be mapped to a scope,
     * define the mapping as `null`, for example:
     *
     * ```php
     * protected $filterScopes = [
     *      'slug' => null
     * ];
     * ```
     *
     * @var array
     */
    protected $filterScopes = [];

    /**
     * The default pagination to use if no page parameters have been provided.
     *
     * If your resource must always be paginated, use this to return the default
     * pagination variables... e.g. `['number' => 1]` for page 1.
     *
     * If this property is null or an empty array, then no pagination will be
     * used if no page parameters have been provided (i.e. every resource
     * will be returned).
     *
     * @var array|null
     */
    protected $defaultPagination = ['number' => 1];

    /**
     * Adapter constructor.
     *
     * @param StandardStrategy $paging
     */
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new PaudInstansiBerkas(), $paging->withUnderscoredMetaKeys());
    }

    /**
     * @param Builder $query
     * @param Collection $filters
     * @return void
     */
    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }

    protected function instansi()
    {
        return $this->hasOne();
    }

    protected function mBerkasPaud()
    {
        return $this->hasOne();
    }

    protected function paudInstansi()
    {
        return $this->hasOne();
    }
}
