<?php

namespace App\Remotes\ElearningRemote;

class UserSyncParam extends CollectionParam
{
    public function __construct(UserSyncParamItem ...$data)
    {
        $this->data = collect($data);
    }
}
