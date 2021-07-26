<?php

namespace App\Services\Instansi;

use App\Models\PaudPeriode;
use Arr;
use Illuminate\Database\Eloquent\Builder;

class PeriodeService
{
    public function index(array $params = []): Builder
    {
        $query = PaudPeriode::query()
            ->where([
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where('nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function create(array $validated): array
    {
        $result = [];

        foreach ($validated as $item) {
            $result[] = PaudPeriode::create([
                'tahun'              => config('paud.tahun'),
                'angkatan'           => config('paud.angkatan'),
                'nama'               => $item['nama'],
                'tgl_daftar_mulai'   => $item['tgl_mulai'],
                'tgl_daftar_selesai' => $item['tgl_selesai'],
                'tgl_diklat_mulai'   => $item['tgl_mulai'],
                'tgl_diklat_selesai' => $item['tgl_selesai'],
            ]);
        }

        return $result;
    }

    public function update(PaudPeriode $periode, array $validated): PaudPeriode
    {
        $periode->nama               = $validated['nama'];
        $periode->tgl_daftar_mulai   = $validated['tgl_mulai'];
        $periode->tgl_daftar_selesai = $validated['tgl_selesai'];
        $periode->tgl_diklat_mulai   = $validated['tgl_mulai'];
        $periode->tgl_diklat_selesai = $validated['tgl_selesai'];

        $periode->save();

        return $periode;
    }
}
