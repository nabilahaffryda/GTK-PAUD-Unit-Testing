<?php

namespace App\Jobs;

use App\Models\Akun;
use App\Models\Instansi;
use App\Models\MGroup;
use App\Models\MVervalPaud;
use App\Services\Instansi\AdminService;
use App\Services\Instansi\PengajarService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateAkunPengajar implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array    $data;
    protected Instansi $instansi;
    protected Akun     $admin;
    protected int      $kGroup;

    public function __construct(Akun $admin, Instansi $instansi, $data, $kGroup)
    {
        $this->admin    = $admin;
        $this->instansi = $instansi;
        $this->data     = $data;
        $this->kGroup   = $kGroup;
    }

    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        $paudAdmin = app(AdminService::class)->create($this->instansi, $this->data);
        app(PengajarService::class)->create($paudAdmin, [
            'k_verval_paud' => MVervalPaud::DISETUJUI,
            'is_tambahan'   => $this->kGroup == MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD ? 1 : 0,
        ]);
    }
}
