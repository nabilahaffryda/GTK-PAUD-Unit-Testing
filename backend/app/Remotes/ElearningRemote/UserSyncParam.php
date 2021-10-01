<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Support\Collection;

class UserSyncParam extends Collection
{
    public function __construct(UserSyncParamItem ...$data)
    {
        parent::__construct($data);
    }
}
