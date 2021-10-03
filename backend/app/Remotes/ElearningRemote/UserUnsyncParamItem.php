<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Contracts\Support\Arrayable;

class UserUnsyncParamItem implements Arrayable
{
    public function __construct(
        public int $pasporId,
        public int $grupId,
        public int $jenisDiklatId,
        public int $kodeInstansi,
    )
    {
    }

    public function toArray()
    {
        return [
            "paspor_id"       => $this->pasporId,
            "grup_id"         => $this->grupId,
            "jenis_diklat_id" => $this->jenisDiklatId,
            "kode_instansi"   => $this->kodeInstansi,
        ];
    }
}
