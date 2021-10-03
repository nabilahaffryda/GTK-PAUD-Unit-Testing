<?php

namespace App\Remotes\ElearningRemote;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class DiklatKelasCreateParam implements Arrayable
{
    public function __construct(
        public string $nama,
        public int    $mapelId,
        public Carbon $tanggalMulai,
        public Carbon $tanggalSelesai,
        public int    $maxPengajar,
        public int    $maxAdminlms,
        public int    $maxPengajartambahan,
        public int    $maxPembimbingpraktik,
        public int    $maxPeserta,
    )
    {
    }

    public function toArray()
    {
        return [
            "nama"                  => $this->nama,
            "mapel_id"              => $this->mapelId,
            "tanggal_mulai"         => $this->tanggalMulai->toDateString(),
            "tanggal_selesai"       => $this->tanggalSelesai->toDateString(),
            "max_pengajar"          => $this->maxPengajar,
            "max_adminlms"          => $this->maxAdminlms,
            "max_pengajartambahan"  => $this->maxPengajartambahan,
            "max_pembimbingpraktik" => $this->maxPembimbingpraktik,
            "max_peserta"           => $this->maxPeserta,
        ];
    }
}
