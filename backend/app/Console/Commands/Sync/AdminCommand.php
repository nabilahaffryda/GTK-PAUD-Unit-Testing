<?php

namespace App\Console\Commands\Sync;

use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

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
            ->get();

        foreach ($kelases as $kelas) {
            app(KelasService::class)->syncAdmin($kelas);
        }

        return 0;
    }
}
