<?php

namespace App\Services\Instansi\Peserta;

use App\Exceptions\FlowException;
use App\Models\MVervalPaud;
use App\Models\PaudInstansi;
use App\Models\PaudPesertaNonptk;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class NonPtkService
{
    /**
     * @throws FlowException
     */
    public static function uploadBerkas(PaudPesertaNonptk $peserta, string $jenis, UploadedFile $file): string
    {
        $ftpPath   = config('filesystems.disks.peserta-nonptk.path');
        $timestamp = date('ymdhis');
        $pathname  = "{$peserta->paud_instansi_id}";
        $filename  = "{$peserta->paud_peserta_nonptk_id}-{$jenis}-{$timestamp}.{$file->getClientOriginalExtension()}";

        if (!Storage::disk('peserta-nonptk')->putFileAs("{$ftpPath}/{$pathname}", $file, $filename)) {
            throw new FlowException("Unggah berkas peserta tidak berhasil");
        }

        return "{$pathname}/{$filename}";
    }

    public function index(PaudInstansi $instansi, array $params)
    {
        return PaudPesertaNonptk::query()
            ->where('paud_instansi_id', '=', $instansi->paud_instansi_id)
            ->when($params['keyword'] ?? null, function (Builder $query, $value) {
                $query->where('paud_peserta_nonptk.nama', 'like', '%' . $value . '%');
            });
    }

    /**
     * @throws FlowException
     */
    public function create(PaudInstansi $instansi, array $params)
    {
        $duplicate = PaudPesertaNonptk::where([
            'paud_instansi_id'      => $instansi->paud_instansi_id,
            'nik'                   => $params['nik'],
            'k_diklat_paud'         => $params['k_diklat_paud'],
            'k_jenjang_diklat_paud' => $params['k_jenjang_diklat_paud'],
        ])->exists();

        if ($duplicate) {
            throw new FlowException("Peserta dengan NIK {$params['nik']} telah terdaftar");
        }

        $peserta = PaudPesertaNonptk::create([
            'paud_instansi_id'      => $instansi->paud_instansi_id,
            'instansi_id'           => $instansi->instansi_id,
            'nama'                  => $params['nama'],
            'nik'                   => $params['nik'],
            'tmp_lahir'             => $params['tmp_lahir'] ?? null,
            'tgl_lahir'             => $params['tgl_lahir'] ?? null,
            'kelamin'               => $params['kelamin'] ?? null,
            'email'                 => $params['email'],
            'no_hp'                 => $params['no_hp'] ?? null,
            'alamat'                => $params['alamat'],
            'k_propinsi'            => $params['k_propinsi'],
            'k_kota'                => $params['k_kota'],
            'unit_kerja'            => $params['unit_kerja'] ?? null,
            'k_diklat_paud'         => $params['k_diklat_paud'],
            'k_jenjang_diklat_paud' => $params['k_jenjang_diklat_paud'],
        ]);

        $peserta->sertifikat_file = static::uploadBerkas($peserta, 'sertifikat', $params['file_sertifikat']);
        $peserta->ktp_file        = static::uploadBerkas($peserta, 'sertifikat', $params['file_ktp']);

        $peserta->sertifikat_nama = substr($params['file_sertifikat']->getClientOriginalName(), 0, 200);
        $peserta->ktp_nama        = substr($params['file_ktp']->getClientOriginalName(), 0, 200);

        $peserta->save();

        return $peserta;
    }

    /**
     * @throws FlowException
     */
    public function update(PaudInstansi $instansi, PaudPesertaNonptk $peserta, array $params)
    {
        if ($peserta->paud_instansi_id != $instansi->paud_instansi_id) {
            throw new FlowException("Peserta tidak dikenali");
        }

        $isNotKandidat = $peserta->paudKelasPesertaLurings()
            ->join('paud_kelas_luring', 'paud_kelas_luring.paud_kelas_luring_id', '=', 'paud_kelas_peserta_luring.paud_kelas_luring_id')
            ->where('paud_kelas_luring.k_verval_paud', '<>', MVervalPaud::KANDIDAT)
            ->exists();

        if ($isNotKandidat) {
            throw new FlowException("Peserta telah diajukan di kelas");
        }

        $duplicate = PaudPesertaNonptk::where([
            'paud_instansi_id'      => $instansi->paud_instansi_id,
            'nik'                   => $params['nik'],
            'k_diklat_paud'         => $params['k_diklat_paud'],
            'k_jenjang_diklat_paud' => $params['k_jenjang_diklat_paud'],
            ['paud_peserta_nonptk_id', '<>', $peserta->paud_peserta_nonptk_id],
        ])->exists();

        if ($duplicate) {
            throw new FlowException("NIK {$params['nik']} telah digunakan");
        }

        $peserta->fill([
            'nama'                  => $params['nama'],
            'nik'                   => $params['nik'],
            'tmp_lahir'             => $params['tmp_lahir'] ?? null,
            'tgl_lahir'             => $params['tgl_lahir'] ?? null,
            'kelamin'               => $params['kelamin'] ?? null,
            'email'                 => $params['email'],
            'no_hp'                 => $params['no_hp'] ?? null,
            'alamat'                => $params['alamat'],
            'k_propinsi'            => $params['k_propinsi'],
            'k_kota'                => $params['k_kota'],
            'unit_kerja'            => $params['unit_kerja'] ?? null,
            'k_diklat_paud'         => $params['k_diklat_paud'],
            'k_jenjang_diklat_paud' => $params['k_jenjang_diklat_paud'],
        ]);

        if (isset($params['file_sertifikat'])) {
            $peserta->sertifikat_nama = substr($params['file_sertifikat']->getClientOriginalName(), 0, 200);
            $peserta->sertifikat_file = static::uploadBerkas($peserta, 'sertifikat', $params['file_sertifikat']);
        }

        if (isset($params['file_ktp'])) {
            $peserta->ktp_file = static::uploadBerkas($peserta, 'sertifikat', $params['file_ktp']);
            $peserta->ktp_nama = substr($params['file_ktp']->getClientOriginalName(), 0, 200);
        }

        $peserta->save();

        return $peserta;
    }

    /**
     * @throws FlowException
     */
    public function delete(PaudInstansi $instansi, PaudPesertaNonptk $peserta)
    {
        if ($peserta->paud_instansi_id != $instansi->paud_instansi_id) {
            throw new FlowException("Peserta tidak dikenali");
        }

        $isKelas = $peserta->paudKelasPesertaLurings()
            ->exists();

        if ($isKelas) {
            throw new FlowException("Peserta telah ditambahkan ke kelas");
        }

        $peserta->delete();

        return $peserta;
    }
}
