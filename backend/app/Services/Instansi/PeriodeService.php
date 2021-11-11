<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\PaudPeriode;
use Arr;
use Carbon\Carbon;
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

    /**
     * @throws FlowException
     */
    public function create(array $validated): array
    {
        $result = [];

        foreach ($validated as $item) {
            /** @var PaudPeriode $other */
            $other = PaudPeriode::query()
                ->where('tgl_diklat_mulai', '<=', $item['tgl_selesai'])
                ->where('tgl_diklat_selesai', '>=', $item['tgl_mulai'])
                ->first();

            if ($other) {
                throw new FlowException("Jadwal {$item['nama']} beririsan dengan {$other->nama}");
            }

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

    /**
     * @throws FlowException
     */
    public function update(PaudPeriode $periode, array $validated): PaudPeriode
    {
        /** @var PaudPeriode $other */
        $other = PaudPeriode::query()
            ->where('tgl_diklat_mulai', '<=', $validated['tgl_selesai'])
            ->where('tgl_diklat_selesai', '>=', $validated['tgl_mulai'])
            ->where('paud_periode_id', '<>', $periode->paud_periode_id)
            ->first();

        if ($other) {
            throw new FlowException("Jadwal {$validated['nama']} beririsan dengan {$other->nama}");
        }

        $periode->nama               = $validated['nama'];
        $periode->tgl_daftar_mulai   = $validated['tgl_mulai'];
        $periode->tgl_daftar_selesai = $validated['tgl_selesai'];
        $periode->tgl_diklat_mulai   = $validated['tgl_mulai'];
        $periode->tgl_diklat_selesai = $validated['tgl_selesai'];

        $periode->save();

        return $periode;
    }

    public function delete(PaudPeriode $periode)
    {
        $periode->delete();
        return $periode;
    }

    /**
     * @throws FlowException
     */
    public function validateWktAjuanAktif(PaudPeriode $periode)
    {
        if ($periode->wkt_ajuan_buka?->isFuture()) {
            throw new FlowException("Pengajuan Diklat {$periode->nama} akan dibuka pada " . Carbon::parse($periode->wkt_ajuan_buka)->formatLocalized('%A, %e %b %Y %H:%M'));
        }

        if ($periode->wkt_ajuan_tutup?->isPast()) {
            throw new FlowException("Pengajuan Diklat {$periode->nama} telah ditutup pada " . Carbon::parse($periode->wkt_ajuan_tutup)->formatLocalized('%A, %e %b %Y %H:%M'));
        }

        if (!$periode->is_aktif) {
            throw new FlowException("Pengajuan Diklat {$periode->nama} dinonaktifkan");
        }
    }
}
