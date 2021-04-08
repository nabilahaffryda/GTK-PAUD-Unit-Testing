<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class BaseResource
 *
 * @property Model $resource
 *
 * @package App\Http\Resources
 */
class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        if (!$this->resource || $this->resource instanceof MissingValue) {
            return null;
        }

        $relationship = [];

        foreach ($this->resource->getRelations() as $item => $relation) {
            $key = Str::snake($item);
            if ($relation instanceof Collection) {
                $relationship[$key] = IdentifierCollection::make($relation);
            } elseif ($relation instanceof Model) {
                $relationship[$key] = IdentifierResource::make($relation);
            }
        }

        return array_filter([
            'type'          => $this->resource->getTable(),
            'id'            => $this->resource->getKey(),
            'attributes'    => $this->modelAttributesToArray(),
            'relationships' => $relationship,
            'meta'          => $this->additional['meta'] ?? null
        ]);
    }

    protected function modelAttributesToArray()
    {
        return $this->resource->attributesToArray();
    }

    public function with($request)
    {
        $included = static::included($this, new Collection());
        if (!$included) {
            return [];
        }

        return [
            'included' => $included
                ->filter()
                ->unique(function ($item) {
                    return $item->getTable() . $item->getKey();
                })
                ->values(),
        ];
    }

    /**
     * @param Model|JsonResource $model
     * @param Collection $included
     * @return Collection
     */
    public static function included($model, Collection $included)
    {
        if ($model instanceof JsonResource && !$model->resource) {
            return null;
        }

        $relations = $model->getRelations();
        foreach ($relations as $key => $relation) {
            if ($relation instanceof Collection) {
                foreach ($relation as $item) {
                    if ($item->getKey()) {
                        $included->add(BaseResource::make($item));
                        static::included($item, $included);
                    }
                }
            } elseif ($relation instanceof Model) {
                if ($relation->getKey()) {
                    $included->add(BaseResource::make($relation));
                    static::included($relation, $included);
                }
            }
        }

        return $included;
    }
}
