<?php

namespace App\Jobs;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\Instansi;
use App\Models\MGroup;
use App\Models\MPetugasPaud;
use App\Models\MUnsurPengajarPaud;
use App\Services\Instansi\AdminService;
use App\Services\Instansi\PetugasService;
use Auth;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAkun implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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

    /**
     * @throws SaveException
     * @throws FlowException
     * @throws GuzzleException
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        Auth::guard('akun')->login($this->admin);

        $paudAdmin = app(AdminService::class)->create($this->instansi, $this->data);

        switch ($this->kGroup) {
            case MGroup::PENGAJAR_DIKLAT_PAUD:
                app(PetugasService::class)->create($paudAdmin, [
                    'k_petugas_paud' => MPetugasPaud::PENGAJAR,
                ]);
                break;

            case MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD:
                app(PetugasService::class)->create($paudAdmin, [
                    'k_petugas_paud'        => MPetugasPaud::PENGAJAR_TAMBAHAN,
                    'k_unsur_pengajar_paud' => $this->data['k_unsur_pengajar_paud'] ?? MUnsurPengajarPaud::UNSUR_GURU,
                ]);
                break;

            case MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD:
                app(PetugasService::class)->create($paudAdmin, [
                    'k_petugas_paud' => MPetugasPaud::PEMBIMBING_PRAKTIK,
                ]);
                break;

            case MGroup::ADM_KELAS_DIKLAT_PAUD:
                app(PetugasService::class)->create($paudAdmin, [
                    'k_petugas_paud' => MPetugasPaud::ADMIN_KELAS,
                ]);
                break;
        }
    }
}
