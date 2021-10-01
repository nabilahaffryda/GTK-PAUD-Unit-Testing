<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Support\Collection;

class KelasEnrollParam extends Collection
{
    public function __construct(KelasEnrollParamItem ...$data)
    {
        parent::__construct($data);
    }
}
