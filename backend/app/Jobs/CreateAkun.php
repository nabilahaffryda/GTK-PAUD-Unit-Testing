<?php

namespace App\Jobs;

use App\Models\Akun;
use App\Models\Instansi;
use App\Models\MGroup;
use App\Models\MVervalPaud;
use App\Services\Instansi\AdminService;
use App\Services\Instansi\PembimbingService;
use App\Services\Instansi\PengajarService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAkun implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;
    protected Instansi $instansi;
    protected Akun $admin;
    protected int $kGroup;

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

        switch ($this->kGroup) {
            case MGroup::PENGAJAR_DIKLAT_PAUD:
                app(PengajarService::class)->create($paudAdmin, [
                    'k_verval_paud' => MVervalPaud::DISETUJUI,
                    'is_tambahan'   => 0,
                ]);
                break;

            case MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD:
                app(PengajarService::class)->create($paudAdmin, [
                    'k_verval_paud' => MVervalPaud::KANDIDAT,
                    'is_tambahan'   => 1,
                    'is_pembimbing' => 0,
                ]);
                break;

            case MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD:
                app(PembimbingService::class)->create($paudAdmin, [
                    'k_verval_paud' => MVervalPaud::KANDIDAT,
                ]);
                break;
        }
    }
}
