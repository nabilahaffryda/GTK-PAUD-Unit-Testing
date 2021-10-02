<?php

namespace App\Remotes\ElearningRemote;

class UserUnsyncParam extends CollectionParam
{
    public function __construct(UserUnsyncParamItem ...$data)
    {
        $this->data = collect($data);
    }
}
