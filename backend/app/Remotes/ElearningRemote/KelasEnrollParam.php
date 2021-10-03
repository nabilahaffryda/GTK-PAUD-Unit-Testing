<?php

namespace App\Remotes\ElearningRemote;

class KelasEnrollParam extends CollectionParam
{
    public function __construct(KelasEnrollParamItem ...$data)
    {
        $this->data = collect($data);
    }
}
