<?php


namespace App\Services\Instansi;


use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use Arr;

class KelasService
{
    public function index(PaudDiklat $paudDiklat, array $params)
    {
        $query = PaudKelas::query()
            ->where('paud_kelas.paud_diklat_id', $paudDiklat->paud_diklat_id);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where('paud_kelas.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function create(PaudDiklat $paudDiklat, array $params)
    {
        $kelas                 = new PaudKelas($params);
        $kelas->paud_diklat_id = $paudDiklat->paud_diklat_id;

        if (!$kelas->save()) {
            throw new SaveException("Proses Tambah Kelas Tidak berhasil");
        }

        return $kelas;
    }

    public function update(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params)
    {
        if ($kelas->paud_diklat_id <> $paudDiklat->paud_diklat_id) {
            throw new FlowException('Kelas tidak ditemukan');
        }

        $kelas->fill($params);
        if (!$kelas->save()) {
            throw new SaveException("Proses simpan data kelas tida berhasil");
        }

        return $kelas;
    }

    public function fetch(PaudDiklat $paudDiklat, PaudKelas $kelas)
    {
        if ($kelas->paud_diklat_id <> $paudDiklat->paud_diklat_id) {
            throw new FlowException('Kelas tidak ditemukan');
        }

        return $kelas->load(['mVervalPaud', 'paudDiklat', 'paudMapelKelas']);
    }
}
