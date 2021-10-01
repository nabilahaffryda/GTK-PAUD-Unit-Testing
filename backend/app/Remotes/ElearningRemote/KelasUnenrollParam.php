<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Support\Collection;

class KelasUnenrollParam extends Collection
{
    public function __construct(KelasUnenrollParamItem ...$data)
    {
        parent::__construct($data);
    }
}
