<?php


namespace App\Services;


use App\Exceptions\FlowException;
use App\Models\Instansi;
use App\Models\PaudInstansi;
use Illuminate\Support\Facades\Storage;

class InstansiService
{
    public function uploadFoto(Instansi $instansi, $foto, $ext)
    {
        $ftpPath   = config('filesystems.disks.instansi-foto.path');
        $timestamp = date('ymdhis');
        $filename  = "{$instansi->k_kota}/{$instansi->k_propinsi}/{$instansi->instansi_id}-{$timestamp}.$ext";

        $path = sprintf("%s/%s", $ftpPath, $filename);
        if (!Storage::disk('instansi-foto')->put($path, $foto)) {
            throw new FlowException("Unggah Foto Lembaga tidak berhasil");
        }

        return $filename;

    }

    public function deleteFoto($filename)
    {
        $ftpPath = config('filesystems.disks.instansi-foto.path');
        $delete  = sprintf("%s/%s", $ftpPath, $filename);
        return Storage::disk('instansi-foto')->delete($delete);
    }

    public function getPaudInstansi(Instansi $instansi)
    {
        return PaudInstansi::whereInstansiId($instansi->instansi_id)
            ->where('angkatan', config('paud.angkatan'))
            ->where('tahun', config('paud.tahun'))
            ->first();
    }

}
