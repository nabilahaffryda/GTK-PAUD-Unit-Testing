<?php

namespace App\Helpers;

class LengthAwarePaginator extends \Illuminate\Pagination\LengthAwarePaginator
{
    protected $others = [];

    public function withOthers(array $others)
    {
        $this->others = array_merge_recursive($this->others, $others);
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $response = [
            'current_page' => $this->currentPage(),
            'data'         => $this->items->toArray(),
            'from'         => $this->firstItem(),
            'last_page'    => $this->lastPage(),
            'per_page'     => $this->perPage(),
            'to'           => $this->lastItem(),
            'total'        => $this->total(),
        ];

        if ($this->others) {
            $response = array_merge($response, $this->others);
        }

        return $response;
    }

    public function format(\Closure $callback)
    {
        $this->getCollection()->each($callback);
        return $this;
    }

}
