<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MGroup;
use App\Models\PaudAdmin;
use App\Services\AkunService;
use Arr;

class AdminKelasService
{
    public function getAdmin(Akun $akun): ?PaudAdmin
    {
        /** @var PaudAdmin|null $admin */
        $admin = PaudAdmin::query()
            ->where([
                'akun_id'  => $akun->akun_id,
                'k_group'  => MGroup::ADM_KELAS_DIKLAT_PAUD,
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ])
            ->with(['akun'])
            ->first();

        return $admin;
    }

    public function fetch(PaudAdmin $admin)
    {
        return $admin->akun
            ->loadMissing([
                'mPropinsi',
                'mKota',
            ]);
    }

    public function getStatusLengkap(PaudAdmin $admin): array
    {
        $akun = $admin->akun;

        $isLengkap1 = $akun->nama
            && $akun->nik
            && $akun->tmp_lahir
            && $akun->tgl_lahir
            && $akun->kelamin
            && $akun->k_propinsi
            && $akun->k_kota
            && $akun->kodepos
            && $akun->alamat
            && $akun->no_hp;

        return [
            'profil' => $isLengkap1,
        ];
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function update(PaudAdmin $admin, array $data, ?string $foto, ?string $ext)
    {
        $akun = $admin->akun;

        $oldFoto = $akun->foto ? $akun->getOriginal('foto') : null;

        $akun->fill(Arr::only($data, [
            'alamat',
            'kelamin',
            'kodepos',
            'k_kota',
            'k_propinsi',
            'nama',
            'nik',
            'nip',
            'no_hp',
            'tgl_lahir',
            'tmp_lahir',
        ]));

        if ($foto && $ext) {
            $akun->foto = app(AkunService::class)->uploadFoto($akun, $foto, $ext);
        }

        if (!$akun->save()) {
            throw new SaveException("Penyimpanan Akun tidak berhasil");
        }

        if ($foto && $ext && $oldFoto) {
            // app(AkunService::class)->deleteFoto($oldFoto);
        }
    }
}
