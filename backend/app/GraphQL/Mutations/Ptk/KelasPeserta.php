<?php

namespace App\GraphQL\Mutations\Ptk;

use App\Exceptions\FlowException;
use App\Models\MKonfirmasiPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelasPeserta;
use App\Remotes\Sertifikat as SertifikatRemote;
use Carbon\Carbon;
use DB;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class KelasPeserta
{
    /**
     * @throws FlowException
     */
    public function konfirmasiSetuju($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasPesertaId = $args['kelasPesertaId'];

        $kelasPeserta = PaudKelasPeserta::findOrFail($kelasPesertaId);
        if ($kelasPeserta->paudKelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Konfirmasi sudah dikunci karena kelas sudah diajukan/diproses');
        }

        if ($kelasPeserta->k_konfirmasi_paud && $kelasPeserta->k_konfirmasi_paud != MKonfirmasiPaud::BELUM_KONFIRMASI) {
            throw new FlowException('Anda telah melakukan konfirmasi');
        }

        $kelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::BERSEDIA;
        $kelasPeserta->save();

        return $kelasPeserta;
    }

    /**
     * @throws FlowException
     */
    public function konfirmasiTolak($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasPesertaId = $args['kelasPesertaId'];

        $kelasPeserta = PaudKelasPeserta::findOrFail($kelasPesertaId);
        if ($kelasPeserta->paudKelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Konfirmasi sudah dikunci karena kelas sudah diajukan/diproses');
        }

        if ($kelasPeserta->k_konfirmasi_paud && $kelasPeserta->k_konfirmasi_paud != MKonfirmasiPaud::BELUM_KONFIRMASI) {
            throw new FlowException('Anda telah melakukan konfirmasi');
        }

        $kelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::TIDAK_BERSEDIA;
        $kelasPeserta->save();

        return $kelasPeserta;
    }

    /**
     * @throws FlowException
     */
    public function konfirmasiBatal($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasPesertaId = $args['kelasPesertaId'];

        $kelasPeserta = PaudKelasPeserta::findOrFail($kelasPesertaId);
        if ($kelasPeserta->paudKelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Konfirmasi sudah dikunci karena kelas sudah diajukan/diproses');
        }

        if ($kelasPeserta->k_konfirmasi_paud && !in_array($kelasPeserta->k_konfirmasi_paud, [MKonfirmasiPaud::BERSEDIA, MKonfirmasiPaud::TIDAK_BERSEDIA])) {
            throw new FlowException('Anda belum melakukan konfirmasi');
        }

        $kelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
        $kelasPeserta->save();

        return $kelasPeserta;
    }

    /**
     * @throws FlowException
     */
    public function validateSurvey($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasId = $args['kelasId'];

        /** @var PaudKelasPeserta $kelasPeserta */
        $kelasPeserta = PaudKelasPeserta::query()
            ->where('paud_kelas_id', '=', $kelasId)
            ->where('ptk_id', '=', ptkId())
            ->firstOrFail();

        if (!$kelasPeserta->paudKelas->is_selesai) {
            throw new FlowException('Kelas belum selesai');
        }

        if ($kelasPeserta->nilai === null) {
            throw new FlowException('Data nilai belum tersedia');
        }

        $quizLayanan = DB::table('quiz_layanan')
            ->where('email', ptk()->email)
            ->where('layanan_id', 'paud-jenjang-dasar')
            ->first();

        if ($quizLayanan) {
            $kelasPeserta->is_survey  = '1';
            $kelasPeserta->wkt_survey = Carbon::now();
            $kelasPeserta->save();
        }

        return [
            'kelasPeserta' => $kelasPeserta,
            'urlSurvey'    => config('simpkb.url') . '/gtk#!/kuesioner/paudjenjangdasar/p/',
        ];
    }

    /**
     * @throws FlowException
     */
    public function validateSertifikat($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasId = $args['kelasId'];

        /** @var PaudKelasPeserta $kelasPeserta */
        $kelasPeserta = PaudKelasPeserta::query()
            ->where('paud_kelas_id', '=', $kelasId)
            ->where('ptk_id', '=', ptkId())
            ->firstOrFail();

        if (!$kelasPeserta->paudKelas->is_selesai) {
            throw new FlowException('Kelas belum selesai');
        }

        if ($kelasPeserta->nilai === null) {
            throw new FlowException('Data nilai belum tersedia');
        }

        if (!$kelasPeserta->is_survey) {
            throw new FlowException('Survey belum diisi');
        }

        if (!$kelasPeserta->url_download) {
            $kelasPeserta->wkt_download = Carbon::now();

            $ptk          = ptk();
            $diklat       = $kelasPeserta->paudKelas->paudDiklat;
            $periode      = $diklat->paudPeriode;
            $paudInstansi = $diklat->paudInstansi;

            $berkases = $paudInstansi->paudInstansiBerkases->keyBy('k_berkas_lpd_paud');
            if (!$berkases->diffKeys([8, 9, 10])) {
                throw new FlowException('Berkas LPD belum lengkap');
            }

            $instansi = $diklat->instansi;

            $params = [
                'k_sertifikat' => '213',
                'angkatan'     => '1',
                'user_id'      => $ptk->ptk_id,
                'model_id'     => $kelasPeserta->paud_kelas_peserta_id,

                'peran'       => 'Peserta',
                'nama'        => $ptk->nama,
                'nomor_surat' => '123456789/B3/GT.00.04/2021',
                'instansi'    => $instansi->nama,

                'tgl_mulai'   => $periode->tgl_diklat_mulai?->toDateString(),
                'tgl_selesai' => $periode->tgl_diklat_selesai?->toDateString(),
                'tgl_cetak'   => $kelasPeserta->wkt_download->toDateString(),
                'predikat'    => strtoupper($kelasPeserta->predikat),

                'data' => [
                    'kota'         => $diklat->mKota->keterangan,
                    'propinsi'     => $diklat->mPropinsi->keterangan,
                    'lokasi'       => $diklat,
                    'lpd_nama'     => $instansi->nama,
                    'lpd_pimpinan' => $paudInstansi->nama_penanggung_jawab,
                    'lpd_lokasi'   => $instansi->mKota->keterangan . ', Provinsi ' . $instansi->mPropinsi->keterangan,

                    'diklat_jenis'   => 'Diklat Berjenjang ',
                    'diklat_jenjang' => 'Tingkat Dasar',
                    'diklat_moda'    => 'Daring Kombinasi',

                    'lpd_url_logo'    => $berkases[8]->url,
                    'lpd_url_ttd'     => $berkases[9]->url,
                    'lpd_url_stempel' => $berkases[10]->url,
                ],
            ];

            $remote = new SertifikatRemote();
            $resp   = $remote->create($params);

            $url = $resp['sertifikat']['url_unduh'] ?? '';
            if (!$url) {
                $nomor = $resp['sertifikat']['nomor'];
                $resp  = $remote->search(['nomor' => $nomor]);
                $url   = $resp['data'][0]['url_unduh'] ?? '';
            }

            $kelasPeserta->url_download = $url;
        }

        return $kelasPeserta;
    }
}
