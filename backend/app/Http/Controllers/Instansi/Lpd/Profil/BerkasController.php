<?php

namespace App\Http\Controllers\Instansi\Lpd\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Profil\BerkasCreateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Models\PaudInstansiBerkas;
use App\Remotes\Sertifikat as SertifikatRemote;
use App\Services\Instansi\LpdService;
use Carbon\Carbon;
use Exception;

class BerkasController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function index(PaudInstansi $paudInstansi)
    {
        $berkases = $paudInstansi
            ->paudInstansiBerkases
            ->loadMissing(['mBerkasLpdPaud']);

        return BaseCollection::make($berkases);
    }

    public function create(PaudInstansi $paudInstansi, BerkasCreateRequest $request)
    {
        return BaseResource::make($this->service->upload($paudInstansi, $request->k_berkas, $request->file));
    }

    /**
     * @throws Exception
     */
    public function delete(PaudInstansiBerkas $berkas)
    {
        if ($berkas->instansi_id != instansiId()) {
            abort(404);
        }

        $this->service->berkasDelete($berkas);
        return BaseResource::make($berkas);
    }

    public function previewSertifikat(PaudInstansi $paudInstansi)
    {
        $tahun = date('Y');

        $berkases = $paudInstansi->paudInstansiBerkases->keyBy('k_berkas_lpd_paud');
        $instansi = $paudInstansi->instansi;

        $params = [
            'k_sertifikat' => '213',
            'angkatan'     => '1',
            'user_id'      => $tahun . '99999999',
            'model_id'     => '1',

            'peran'       => 'Peserta',
            'nama'        => 'NAMA PESERTA DIKLAT',
            'nomor_surat' => '1/XX/XX.00.00/' . $tahun,
            'instansi'    => 'UNIT KERJA',

            'tgl_mulai'   => Carbon::now()->toDateString(),
            'tgl_selesai' => Carbon::now()->toDateString(),
            'tgl_cetak'   => Carbon::now()->toDateString(),
            'predikat'    => 'AMAT BAIK',

            'data' => [
                'lpd_nama'     => $instansi->nama,
                'lpd_pimpinan' => $paudInstansi->nama_penanggung_jawab,
                'lpd_lokasi'   => $instansi->mKota->keterangan,

                'kota'     => 'Kota Unit',
                'propinsi' => 'Unit',
                'lokasi'   => 'Kota Unit, Provinsi Unit',

                'diklat_jenis'   => 'Diklat Berjenjang ',
                'diklat_jenjang' => 'Tingkat Dasar',
                'diklat_moda'    => 'Moda Daring Kombinasi',

                'lpd_url_logo'    => $berkases[8]?->url ?? '',
                'lpd_url_ttd'     => $berkases[9]?->url ?? '',
                'lpd_url_stempel' => $berkases[10]?->url ?? '',
            ],
        ];

        $remote   = new SertifikatRemote();
        $response = $remote->preview($params);
        return response($response->getBody(), 200, [
            'Content-Type' => $response->getHeader('Content-Type')[0],
            'Content-Disposition: inline; filename="sertifikat-preview.pdf"',
        ]);
    }
}
