<?php


namespace App\Services\Instansi;


use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Instansi;
use App\Models\PaudDiklat;
use App\Models\PaudPeriode;
use Arr;
use Carbon\Carbon;

class DiklatService
{

    public function index(Instansi $instansi, array $params)
    {
        $query = PaudDiklat::query()
            ->where('instansi_id', '=', $instansi->instansi_id)
            ->with([
                'paudPeriode',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('paud_diklat.nama', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    public function create(Instansi $instansi, array $params)
    {
        $tglMulai   = Carbon::createFromFormat('Y-m-d', $params['tgl_daftar_mulai']);
        $tglSelesai = Carbon::createFromFormat('Y-m-d', $params['tgl_daftar_selesai']);

        $periode = PaudPeriode::query()
            ->where('nama', '=', $params['periode_diklat'])
            ->where('tgl_daftar_mulai', '=', $tglMulai->format('Y-m-d'))
            ->where('tgl_daftar_selesai', '=', $tglSelesai->format('Y-m-d'))->first();

        if ($tglMulai->gt($tglSelesai)) {
            throw new FlowException("Tanggal Mulai Pendaftaran tidak boleh lebih besar dari Tanggal Selesai Pendaftaran");
        }

        if (!$periode) {
            $periode = new PaudPeriode([
                'nama'               => $params['periode_diklat'],
                'tgl_daftar_mulai'   => $tglMulai->format('Y-m-d'),
                'tgl_daftar_selesai' => $tglSelesai->format('Y-m-d'),
            ]);
        }

        $periode->save();

        $paudDiklat = new PaudDiklat($params);

        $paudDiklat->instansi_id     = $instansi->instansi_id;
        $paudDiklat->paud_periode_id = $periode->paud_periode_id;

        $paudDiklat->save();

        return $paudDiklat;
    }

    public function fetch(PaudDiklat $paudDiklat)
    {
        $paudDiklat->load([
            'instansi',
            'mPropinsi',
            'mKota',
        ]);

        return $paudDiklat;
    }

    public function update(?Instansi $instansi, PaudDiklat $paudDiklat, array $params)
    {
        $paudDiklat->fill($params);

        if (!$paudDiklat->save()) {
            throw new SaveException('Proses simpan data diklat tidak berhasil');
        }

        return $paudDiklat->load([
            'instansi',
            'mPropinsi',
            'mKota',
        ]);
    }

    public function delete(PaudDiklat $paudDiklat)
    {
        $instansi = $paudDiklat->instansi;

        $paudDiklat->delete();

        return $instansi;
    }
}
