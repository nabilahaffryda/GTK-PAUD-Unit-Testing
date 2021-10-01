<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Contracts\Support\Arrayable;

class KelasUnenrollParamItem implements Arrayable
{
    public function __construct(
        public int $pasporId,
        public int $grupId,
    )
    {
    }

    public function toArray()
    {
        return [
            "paspor_id" => $this->pasporId,
            "grup_id"   => $this->grupId,
        ];
    }
}
