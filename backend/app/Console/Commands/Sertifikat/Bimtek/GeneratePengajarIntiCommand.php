<?php

namespace App\Console\Commands\Sertifikat\Bimtek;

use App\Models\Akun;
use App\Remotes\Sertifikat as SertifikatRemote;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class GeneratePengajarIntiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sertifikat-bimtek:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sertifikat bimtek';

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
        $json = json_decode(file_get_contents(storage_path('region-all.json')), true);

        $data = [];
        foreach ($json as $item) {
            $email = strtolower($item['email']);

            $data[$email] = $item;
        }

        /** @var Akun[]|Collection $akuns */
        $akuns = Akun::query()->whereIn('email', array_keys($data))->get()->keyBy('email');

        file_put_contents(storage_path('region-result.txt'), '');

        foreach ($akuns as $email => $akun) {
            $item = $data[strtolower($email)] ?? null;
            if (!$item) {
                continue;
            }

            try {
                $params = [
                    'k_sertifikat' => 110,
                    'angkatan'     => $item['regional'],
                    'model_id'     => $akun->paspor_id,
                    'user_id'      => $akun->paspor_id,
                    'nama'         => strtoupper($item['nama']),
                    'instansi'     => strtoupper($item['instansi']),
                    'data'         => [
                        'kota'     => strtoupper($item['kota']),
                        'propinsi' => strtoupper($item['prov']),
                    ],
                    'peran'        => 'Peserta',
                ];

                $remote = new SertifikatRemote();
                $resp   = $remote->create($params);

                $url = $resp['sertifikat']['url_unduh'] ?? '';
                if (!$url) {
                    $nomor = $resp['sertifikat']['nomor'];

                    $resp = $remote->search(['nomor' => $nomor]);

                    $url = $resp['data'][0]['url_unduh'] ?? '';
                }

                $this->info($email . ':' . $url);

                file_put_contents(storage_path('region-result.txt'), "{$email}\t{$url}\n", FILE_APPEND);

            } catch (\Exception $exception) {
                $this->info($exception->getMessage());
            }

            // break;
        }

        return 0;
    }
}
