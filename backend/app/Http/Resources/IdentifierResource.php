<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BaseResource
 *
 * @property Model $resource
 *
 * @package App\Http\Resources
 */
class IdentifierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => $this->resource->getTable(),
                'id'   => $this->resource->getKey(),
            ]
        ];
    }
}
