<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

abstract class CollectionParam implements Arrayable
{
    protected Collection $data;

    public function getData()
    {
        return $this->data;
    }

    public function toArray()
    {
        return $this->data->toArray();
    }

    public static function make(...$item)
    {
        return new static(...$item);
    }
}
