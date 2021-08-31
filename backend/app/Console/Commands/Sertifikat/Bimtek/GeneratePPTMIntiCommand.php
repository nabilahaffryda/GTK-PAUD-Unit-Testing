<?php

namespace App\Console\Commands\Sertifikat\Bimtek;

use App\Models\Akun;
use App\Remotes\Sertifikat as SertifikatRemote;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class GeneratePPTMIntiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sertifikat-bimtek:generate-pptm-inti {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sertifikat bimtek pembekalan pembimbing praktik tugas mandiri inti';

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
     * @throws UnsupportedTypeException
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function handle()
    {
        $filePath = $this->argument('filename');

        $reader = ReaderEntityFactory::createReaderFromFile($filePath);
        $reader->open($filePath);

        $data = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                // index 0 = header
                if ($index == 1) {
                    continue;
                }

                $cells = $row->getCells();

                $prov  = $cells[2]->getValue();
                $kota  = $cells[3]->getValue();
                $email = strtolower($cells[4]->getValue());

                $data[$email] = [
                    'kota' => $kota,
                    'prov' => $prov,
                ];
            }

            // hanya loop sheet pertama
            break;
        }

        /** @var Akun[]|Collection $akuns */
        $akuns = Akun::query()->whereIn('email', array_keys($data))->get()->keyBy('email');

        $fileResult = storage_path('result-pptm-inti.txt');
        file_put_contents($fileResult, '');

        foreach ($akuns as $email => $akun) {
            $item = $data[strtolower($email)] ?? null;
            if (!$item) {
                continue;
            }

            try {
                $params = [
                    'k_sertifikat' => 111,
                    'angkatan'     => 1,
                    'model_id'     => $akun->paspor_id,
                    'user_id'      => $akun->paspor_id,
                    'instansi'     => 'GTK PAUD',
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

                file_put_contents($fileResult, "{$email}\t{$url}\n", FILE_APPEND);

            } catch (\Exception $exception) {
                $this->info($exception->getMessage());
            }

            // break;
        }

        return 0;
    }
}
