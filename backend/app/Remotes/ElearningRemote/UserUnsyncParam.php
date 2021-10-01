<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Support\Collection;

class UserUnsyncParam extends Collection
{
    public function __construct(UserUnsyncParamItem ...$data)
    {
        parent::__construct($data);
    }
}
