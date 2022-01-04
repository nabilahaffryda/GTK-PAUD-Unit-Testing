<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use Exception;
use Illuminate\Http\UploadedFile;
use Storage;

class KelasJadwalService
{
    /**
     * @throws FlowException
     * @throws Exception
     */
    public function uploadJadwal(int $instansiId, int $kelasId, ?string $fileOld, UploadedFile $file)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['pdf', 'jpeg', 'jpg', 'png'])) {
            throw new FlowException("Jenis berkas jadwal tidak dikenali");
        }

        $timestamp = date('ymdhis');
        $random    = random_int(10000, 99999);

        $name = "{$kelasId}-{$timestamp}-{$random}." . $ext;
        $path = "{$instansiId}";

        $ftpPath = config('filesystems.disks.kelas-jadwal.path') . "/" . $path;
        if (!Storage::disk('kelas-jadwal')->putFileAs($ftpPath, $file, $name)) {
            throw new FlowException("Unggah berkas jadwal tidak berhasil");
        }

        $hapusOld = config('paud.kelas-jadwal.hapus-file-lama');
        if ($fileOld && $hapusOld) {
            try {
                Storage::disk('kelas-jadwal')->delete($fileOld);
            } catch (Exception) {
            }
        }

        return "{$path}/{$name}";
    }
}
