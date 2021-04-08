<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class BaseCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = BaseResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function with($request)
    {
        $included = new Collection();

        foreach ($this->collection as $item) {
            $included = BaseResource::included($item, $included);
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
}
