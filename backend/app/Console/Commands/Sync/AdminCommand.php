<?php

namespace App\Console\Commands\Sync;

use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:admin {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync admin ke LMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $id = $this->argument('id');

        /** @var PaudKelas[]|Collection $kelases */
        $kelases = PaudKelas::query()
            ->when($id, function ($query, $value) {
                $query->where('paud_kelas_id', $value);
            })
            ->whereNotNull('lms_kelas_id')
            ->whereHas('paudDiklat', function (Builder $query) {
                $now = Carbon::now();
                $query
                    ->join('paud_periode', 'paud_periode.paud_periode_id', '=', 'paud_diklat.paud_periode_id')
                    ->where('paud_periode.tgl_diklat_mulai', '<=', $now)
                    ->where('paud_periode.tgl_diklat_selesai', '>=', $now)
                    ->where('paud_periode.is_aktif', '1');
            })
            ->orderBy('paud_kelas_id')
            ->get();

        $this->info('sync: ' . $kelases->pluck('paud_kelas_id')->implode(', '));
        $gagals = [];

        foreach ($kelases as $kelas) {
            $this->info('proses ' . $kelas->paud_kelas_id);

            $try = 2;
            while ($try > 0) {
                try {
                    app(KelasService::class)->syncAdmin($kelas);
                    break;
                } catch (Exception) {
                }

                $try--;
                if ($try <= 0) {
                    $gagals[] = $kelas->paud_kelas_id;
                }
            }
        }

        if ($gagals) {
            $this->warn('gagal: ' . implode(', ', $gagals));
        }

        return 0;
    }
}
