<?php

namespace App\Console\Commands\Kelas;

use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;
use Illuminate\Console\Command;

class SyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kelas:sync {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync kelas ke SIMEL';

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
     */
    public function handle()
    {
        $id = $this->argument('id');

        $kelas = PaudKelas::findOrFail($id);

        app(KelasService::class)->sync($kelas);

        return 0;
    }
}
