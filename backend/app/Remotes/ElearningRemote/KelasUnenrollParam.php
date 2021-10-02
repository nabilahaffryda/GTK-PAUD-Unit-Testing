<?php

namespace App\Remotes\ElearningRemote;

class KelasUnenrollParam extends CollectionParam
{
    public function __construct(KelasUnenrollParamItem ...$data)
    {
        $this->data = collect($data);
    }
}
