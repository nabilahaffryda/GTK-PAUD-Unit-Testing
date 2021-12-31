<?php

namespace App\Http\Controllers\Instansi\Petugas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Luring\Laporan\IndexRequest;
use App\Http\Requests\Instansi\Petugas\Luring\Laporan\UploadRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MVervalPaud;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Services\Instansi\DiklatLuringService;
use App\Services\Instansi\KelasLuringPesertaService;
use Carbon\Carbon;
use Exception;
use Storage;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    /**
     * @throws FlowException
     */
    public function upload(PaudKelasLuring $kelas, UploadRequest $request)
    {
        app(DiklatLuringService::class)->validateSelesai($kelas->paudDiklatLuring);

        $file       = $request->file;
        $kelasId    = $kelas->paud_kelas_luring_id;
        $instansiId = $kelas->paudDiklatLuring->instansi_id;
        $fileOld    = $kelas->file_laporan;

        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['pdf', 'jpeg', 'jpg', 'png'])) {
            throw new FlowException("Jenis berkas jadwal tidak dikenali");
        }

        $timestamp = date('ymdhis');
        $random    = random_int(10000, 99999);

        $name = "{$kelasId}-{$timestamp}-{$random}." . $ext;
        $path = "{$instansiId}";

        $ftpPath = config('filesystems.disks.kelas-laporan.path') . "/" . $path;
        if (!Storage::disk('kelas-laporan')->putFileAs($ftpPath, $file, $name)) {
            throw new FlowException("Unggah berkas jadwal tidak berhasil");
        }

        $hapusOld = config('paud.kelas-laporan.hapus-file-lama');
        if ($fileOld && $hapusOld) {
            try {
                Storage::disk('kelas-laporan')->delete($fileOld);
            } catch (Exception) {
            }
        }

        $filename = "{$path}/{$name}";

        $kelas->file_laporan = $filename;
        $kelas->save();

        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function deleteUpload(PaudKelasLuring $kelas)
    {
        app(DiklatLuringService::class)->validateSelesai($kelas->paudDiklatLuring);

        try {
            Storage::disk('kelas-laporan')->delete($kelas->file_laporan);
        } catch (Exception) {
        }

        $kelas->file_laporan = null;
        $kelas->save();

        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function kirim(PaudKelasLuring $kelas)
    {
        app(DiklatLuringService::class)->validateSelesai($kelas->paudDiklatLuring);
        if (!$kelas->file_laporan) {
            throw new FlowException("Berkas laporan belum diunggah");
        }

        $kelas->laporan_k_verval_paud = MVervalPaud::DIAJUKAN;
        $kelas->laporan_wkt_ajuan     = Carbon::now();
        $kelas->save();

        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function batal(PaudKelasLuring $kelas)
    {
        app(DiklatLuringService::class)->validateSelesai($kelas->paudDiklatLuring);
        if (!$kelas->file_laporan) {
            throw new FlowException("Berkas laporan belum diunggah");
        }

        if ($kelas->laporan_k_verval_paud != MVervalPaud::DIAJUKAN) {
            throw new FlowException("Berkas laporan belum diajukan");
        }

        $kelas->laporan_k_verval_paud = null;
        $kelas->laporan_wkt_ajuan     = null;
        $kelas->save();

        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelasLuring $kelas, IndexRequest $request)
    {
        $pesertas = app(KelasLuringPesertaService::class)
            ->index($kelas, $request->all())
            ->with([
                'ptk',
                'PaudPesertaNonptk',
            ]);

        return BaseCollection::make($pesertas->paginate((int)$request->get('count', 10)));
    }

    public function nilai(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta)
    {
        if ($kelas->paud_kelas_luring_id != $peserta->paud_kelas_luring_id) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $nilais = $peserta->paudKelasPesertaLuringNilais()
            ->select('paud_kelas_peserta_luring_nilai.*')
            ->join('m_instrumen_nilai_luring_paud', 'm_instrumen_nilai_luring_paud.k_instrumen_nilai_luring_paud', '=', 'paud_kelas_peserta_luring_nilai.k_instrumen_nilai_luring_paud')
            ->orderBy('m_instrumen_nilai_luring_paud.urutan')
            ->with('MInstrumenNilaiLuringPaud')
            ->get();

        return BaseCollection::make($nilais);
    }
}
