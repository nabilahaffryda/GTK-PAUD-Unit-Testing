<?php

namespace App\Remotes\ElearningRemote;

use Illuminate\Contracts\Support\Arrayable;

class UserSyncParamItem implements Arrayable
{
    public function __construct(
        public int    $pasporId,
        public string $nama,
        public string $email,
        public int    $grupId,
        public int    $jenisDiklatId,
        public int    $kodeInstansi,
    )
    {
    }

    public function toArray()
    {
        return [
            "paspor_id"       => $this->pasporId,
            "nama"            => $this->nama,
            "email"           => $this->email,
            "grup_id"         => $this->grupId,
            "jenis_diklat_id" => $this->jenisDiklatId,
            "kode_instansi"   => $this->kodeInstansi,
        ];
    }
}
