<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Jobs\CreateAkunPengajar;
use App\Models\Akun;
use App\Models\AkunInstansi;
use App\Models\Instansi;
use App\Models\MGroup;
use App\Models\MJenisInstansi;
use App\Models\MStatusEmail;
use App\Models\PaudAdmin;
use App\Models\Ptk;
use App\Remotes\Paspor\User;
use App\Services\AkunService;
use Arr;
use Box\Spout\Common\Entity\Row;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Str;

class AdminService
{
    /**
     * AdminService constructor.
     * @param AkunService $akunService
     */
    public function __construct(
        protected AkunService $akunService,
    )
    {
    }

    /**
     * @param Instansi $instansi
     * @param array $params
     * @return Builder
     */
    public function query(Instansi $instansi, $params = [])
    {
        $condition = [
            'paud_admin.tahun'    => $params['tahun'] ?? config('paud.tahun'),
            'paud_admin.angkatan' => $params['angkatan'] ?? config('paud.angkatan'),
        ];

        if ($instansi->k_jenis_instansi != MJenisInstansi::PAUD || ($params['k_group'] ?? 0) != MGroup::AP_LPD_DIKLAT_PAUD) {
            $condition['paud_admin.instansi_id'] = $instansi->instansi_id;
        }

        $query = PaudAdmin::query()
            ->join('akun', 'paud_admin.akun_id', '=', 'akun.akun_id')
            ->join('akun_instansi', function (JoinClause $query) {
                $query->whereColumn([
                    'akun_instansi.akun_id'     => 'paud_admin.akun_id',
                    'akun_instansi.k_group'     => 'paud_admin.k_group',
                    'akun_instansi.instansi_id' => 'paud_admin.instansi_id',
                ]);
            })
            ->select(['paud_admin.*'])
            ->where($condition)
            ->with([
                'akun.mPropinsi',
                'akun.mKota',
                'akun.instansiPropinsi',
                'akun.instansiKota',
                'mGroup',
                'akun.mGolongan',
            ]);

        if (($params['k_group'] ?? 0) == MGroup::AP_LPD_DIKLAT_PAUD) {
            $query->with(['instansi']);
        }

        if (isset($params['k_group'])) {
            $query->where('paud_admin.k_group', '=', $params['k_group']);

        } else {
            // pastikan hanya mengembalikan data yang sesuai dengan childgroup
            $kGroups = AkunService::childGroups(akun(), $instansi)
                ->pluck('k_group');
            $query->whereIn('paud_admin.k_group', $kGroups);
        }

        if (isset($params['is_aktif'])) {
            $query->where([
                'akun.is_aktif'          => $params['is_aktif'],
                'akun_instansi.is_aktif' => $params['is_aktif'],
                'paud_admin.is_aktif'    => $params['is_aktif'],
            ]);
        }

        if (isset($params['kelamin'])) {
            $query->where([
                'akun.kelamin' => $params['kelamin'],
            ]);
        }

        $email = strtolower(akun()->email);
        if (!(Str::is('*@jayantara.*', $email) || Str::is('*@kemdikbud.id', $email))) {
            $query->where('akun.email', 'NOT LIKE', '%@jayantara.%');
            $query->where('akun.email', 'NOT LIKE', '%@kemdikbud.id');
        }

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('akun.nama', 'like', '%' . $keyword . '%')
                    ->orWhere('akun.email', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    /**
     * @param Instansi $instansi
     * @param array $params
     * @return LengthAwarePaginator
     * @throws IOException
     * @throws WriterNotOpenedException
     */
    public function download(Instansi $instansi, $params = [])
    {
        $query = $this->query($instansi, $params);

        if (Arr::get($params, 'format') == 'json') {
            return $query->paginate(10);
        }

        $header = [
            'No',
            'Nama Lengkap',
            'NIK',
            'NIP',
            'Pangkat/Golongan',
            'Alamat Rumah',
            'Kota/Kabupaten',
            'Provinsi',
            'Kode Pos',
            'Instansi Asal',
            'Alamat Instansi Asal',
            'Kota/Kabupaten Instansi Asal',
            'Provinsi Instansi Asal',
            'Kode Pos Instansi Asal',
            'Nomor HP/ WA',
            'Akun SIMPKB',
            'Peran',
            'Instansi Tugas',
            'NPWP',
            'Nama Sesuai Rekening',
            'Nama Bank',
            'Bank Cabang',
            'Nomor Rekening',
        ];

        $date     = Carbon::now()->format('dmYHi');
        $filename = "Laporan-Admin-{$date}.xlsx";

        $defaultStyle = (new StyleBuilder())
            ->setFontName('Calibri')
            ->setFontSize(11)
            ->setShouldWrapText(false)
            ->build();
        $headerStyle  = clone $defaultStyle;
        $headerStyle->setFontBold();

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($filename);
        $writer->setDefaultRowStyle($defaultStyle);

        $writer->addRow(WriterEntityFactory::createRowFromArray($header, $headerStyle));

        $i = 1;
        $query->chunk(1000, function ($paudAdmins) use ($writer, &$i) {
            foreach ($paudAdmins as $paudAdmin) {
                /** @var PaudAdmin $paudAdmin */
                $akun = $paudAdmin->akun;

                $row = [
                    $i++,
                    $akun->nama,
                    $akun->nik,
                    $akun->nip,
                    $akun->mGolongan->keterangan ?? null,
                    $akun->jabatan,
                    $akun->alamat,
                    $akun->mKota->keterangan ?? null,
                    $akun->mKota->mPropinsi->keterangan ?? null,
                    $akun->kodepos,
                    $akun->instansi_asal,
                    $akun->instansi_alamat,
                    $akun->mKotaInstansi->keterangan ?? null,
                    $akun->mKotaInstansi->mPropinsi->keterangan ?? null,
                    $akun->instansi_kodepos,
                    $akun->no_hp,
                    $akun->email,
                    $paudAdmin->mGroup->keterangan,
                    $paudAdmin->instansi->nama,
                    $akun->npwp,
                    $akun->rekening_nama,
                    $akun->rekening_bank,
                    $akun->rekening_cabang,
                    $akun->rekening_nomor,
                ];

                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }
        });

        $writer->close();
        return null;
    }

    public function downloadAktivasi(Instansi $instansi, $params = [])
    {
        $q = $this->query($instansi, $params)
            ->whereNotNull('akun.passwd');

        if (Arr::get($params, 'format') == 'json') {
            return $q->paginate(10);
        }

        $header = [
            'NO',
            'EMAIL',
            'NAMA',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'NIP',
            'NOMOR TELEPON',
            'NOMOR HP',
            'PERAN',
            'KODE AKTIVASI',
        ];

        $date     = Carbon::now()->format('dmYHi');
        $filename = "Laporan-Aktivasi-Akun-{$date}.xlsx";

        $defaultStyle = (new StyleBuilder())
            ->setFontName('Calibri')
            ->setFontSize(11)
            ->setShouldWrapText(false)
            ->build();
        $headerStyle  = clone $defaultStyle;
        $headerStyle->setFontBold();

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($filename);
        $writer->setDefaultRowStyle($defaultStyle);

        $writer->addRow(WriterEntityFactory::createRowFromArray($header, $headerStyle));

        $i = 1;
        $q->chunk(1000, function ($akunInstansis) use ($writer, &$i) {
            foreach ($akunInstansis as $akunInstansi) {
                /** @var AkunInstansi $akunInstansi */
                $akun = $akunInstansi->akun;

                $row = [
                    $i++,
                    $akun->email,
                    $akun->nama,
                    $akun->kelamin,
                    $akun->tmp_lahir,
                    $akun->tgl_lahir ? $akun->tgl_lahir->format('Y-m-d') : null,
                    $akun->nip,
                    $akun->no_telpon,
                    $akun->no_hp,
                    $akunInstansi->mGroup->keterangan,
                    $akun->passwd,
                ];

                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }
        });

        $writer->close();
    }

    /**
     * @param Instansi $instansi
     * @param array $params
     * @return PaudAdmin
     * @throws SaveException
     * @throws GuzzleException
     */
    public function create(Instansi $instansi, $params = [])
    {
        if ($instansi->k_jenis_instansi != MJenisInstansi::PAUD || !isset($params['instansi_id'])) {
            $params['instansi_id'] = $instansi->instansi_id;
        }

        if (Str::is("*@guruku.id", $params['email'])) {
            if (!Ptk::whereEmail($params['email'])->whereIsAktif(1)->first()) {
                throw new FlowException("Akun SIMPKB {$params['email']} tidak ditemukan");
            }
        }

        /** @var Akun $akun */
        $akun = Akun::query()
            ->firstOrNew([
                'email' => $params['email'],
            ], [
                'k_status_email' => MStatusEmail::ANGGAP_AKTIVASI,
            ])
            ->fill($params);

        $paspor = new User();
        $passwd = $this->akunService->passwd();

        $user = $paspor->getUserByEmail($akun->email);
        if (!$user) {
            $users = [
                [
                    'nama'     => $akun->nama,
                    'passwd'   => $passwd,
                    'email'    => $akun->email,
                    'is_aktif' => '1',
                    'is_email' => '1',
                    'admin_id' => akun()->paspor_id
                ]
            ];

            $paspor->add($users, [config('services.paspor.layanan_id')], akun()->paspor_id);
            $user = $paspor->getUserByEmail($akun->email);
        }

        if (!$akun->paspor_id && $user) {
            $akun->akun_id   = $user['userid'];
            $akun->paspor_id = $user['userid'];
            $akun->passwd    = $passwd;
        }

        if (!$akun->save()) {
            throw new SaveException("Penyimpanan Data Admin tidak berhasil");
        }

        $akunInstansi = AkunInstansi::firstOrNew([
            'akun_id'     => $akun->akun_id,
            'k_group'     => $params['k_group'],
            'instansi_id' => $params['instansi_id'],
        ], [
            'is_aktif' => 1,
        ]);

        if ($akunInstansi->exists && !$akunInstansi->is_aktif) {
            $akunInstansi->is_aktif = 1;
        }

        if (!$akunInstansi->save()) {
            throw new SaveException("Penyimpanan Instansi Akun tidak berhasil");
        }

        $paudAdmin = PaudAdmin::firstOrNew([
            'akun_id'     => $akun->akun_id,
            'k_group'     => $params['k_group'],
            'instansi_id' => $params['instansi_id'],
            'tahun'       => config('paud.tahun'),
            'angkatan'    => config('paud.angkatan'),
        ], [
            'is_aktif' => 1,
        ]);

        if ($paudAdmin->exists && !$paudAdmin->is_aktif) {
            $paudAdmin->is_aktif = 1;
        }

        if (!$paudAdmin->save()) {
            throw new SaveException("Penyimpanan Admin Paud tidak berhasil");
        }

        $paudAdmin->load(['akun', 'instansi', 'mGroup']);
        $paudAdmin->akun->makeVisible(['passwd']);

        //TODO: Kirim email data aktivasi

        return $paudAdmin;
    }

    /**
     * @param Instansi $instansi
     * @param PaudAdmin $paudAdmin
     * @return PaudAdmin
     */
    public function fetch(Instansi $instansi, PaudAdmin $paudAdmin)
    {
        $paudAdmin->akun
            ->load([
                'mGolongan',
                'mPropinsi',
                'mKota',
                'instansiPropinsi',
                'instansiKota',
            ])
            ->makeVisible('passwd');

        $paudAdmin->akun->load([
            'akunInstansis' => function ($query) use ($instansi) {
                $query->where('instansi_id', $instansi->instansi_id);
            },
            'paudAdmins'    => function ($query) use ($instansi) {
                $query->where('instansi_id', $instansi->instansi_id);
            }
        ]);

        return $paudAdmin;
    }

    /**
     * @param Instansi $instansi
     * @param PaudAdmin $paudAdmin
     * @param array $params
     * @return PaudAdmin
     * @throws FlowException
     * @throws SaveException
     */
    public function update(Instansi $instansi, PaudAdmin $paudAdmin, $params = [])
    {
        if ($instansi->k_jenis_instansi != MJenisInstansi::PAUD && $paudAdmin->instansi_id != $instansi->instansi_id) {
            throw new FlowException('Akun tidak terdaftar di instansi terkait');
        }

        $akunInstansi = AkunInstansi::firstOrCreate([
            'akun_id'     => $paudAdmin->akun_id,
            'k_group'     => $paudAdmin->k_group,
            'instansi_id' => $paudAdmin->instansi_id,
        ], [
            'is_aktif' => 1,
        ]);

        $paudAdmin->k_group = $params['k_group'];
        if ($paudAdmin->isDirty() && !$paudAdmin->save()) {
            throw new SaveException("Penyimpanan Peran Admin tidak berhasil");
        }

        $akunInstansi->k_group = $paudAdmin->k_group;
        if ($akunInstansi->isDirty() && !$akunInstansi->save()) {
            throw new SaveException("Penyimpanan Peran Admin tidak berhasil");
        }

        $akun = $paudAdmin->akun;
        $akun->fill(Arr::except($params, ['email']));
        if (!$akun->save()) {
            throw new SaveException("Penyimpanan Data Admin tidak berhasil");
        }

        return $paudAdmin;
    }

    /**
     * @param Instansi $instansi
     * @param PaudAdmin $paudAdmin
     * @return PaudAdmin
     * @throws FlowException
     * @throws SaveException
     * @throws Exception
     */
    public function delete(Instansi $instansi, PaudAdmin $paudAdmin)
    {
        if ($instansi->k_jenis_instansi != MJenisInstansi::PAUD && $paudAdmin->instansi_id != $instansi->instansi_id) {
            throw new FlowException('Akun tidak terdaftar di instansi terkait');
        }

        $jmlPaudAdmin = $paudAdmin->akun->paudAdmins()->count();

        if (!$paudAdmin->delete()) {
            throw new SaveException('Hapus akun admin dari instansi terkait tidak berhasil');
        }

        if ($jmlPaudAdmin != 1) {
            return $paudAdmin;
        }

        $jmlAkunInstansi = $paudAdmin->akun->akunInstansis()->count();

        /** @var AkunInstansi $akunInstansi */
        $akunInstansi = AkunInstansi::query()
            ->where([
                'akun_id'     => $paudAdmin->akun_id,
                'k_group'     => $paudAdmin->k_group,
                'instansi_id' => $paudAdmin->instansi_id,
            ])
            ->first();

        if ($akunInstansi && !$akunInstansi->delete()) {
            throw new SaveException('Hapus akun admin dari instansi terkait tidak berhasil');
        }

        if ($jmlAkunInstansi != 1) {
            return $paudAdmin;
        }

        try {
            $paudAdmin->akun->delete();
        } catch (Exception) {
            throw new SaveException('Hapus akun admin tidak berhasil karena masih terikat data lain');
        }

        return $paudAdmin;
    }

    /**
     * @param Instansi $instansi
     * @param PaudAdmin $paudAdmin
     * @return PaudAdmin
     * @throws FlowException
     * @throws GuzzleException
     * @throws SaveException
     */
    public function resetPasword(Instansi $instansi, PaudAdmin $paudAdmin)
    {
        if ($instansi->k_jenis_instansi != MJenisInstansi::PAUD && $paudAdmin->instansi_id != $instansi->instansi_id) {
            throw new FlowException('Akun tidak terdaftar di instansi terkait');
        }

        if ($this->akunService->resetPaspor($paudAdmin->akun_id, true)) {
            $paudAdmin
                ->load(['akun', 'mGroup'])
                ->akun
                ->makeVisible('passwd');

            return $paudAdmin;
        }

        throw new SaveException('Reset Password Gagal');
    }

    public function upload(Akun $admin, Instansi $instansi, UploadedFile $file, $kGroup)
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->getRealPath());

        $rules = [
            'nama'      => ['required', 'string', 'max:50'],
            'email'     => ['required', 'email:dns,rfc'],
            'tmp_lahir' => ['nullable', 'string'],
            'tgl_lahir' => ['nullable', 'date_format:Y-m-d'],
        ];

        $data    = [];
        $batches = [];
        $errors  = [];
        $uniques = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            if ($sheet->getName() !== 'Data') {
                break;
            }

            foreach ($sheet->getRowIterator() as $index => $row) {
                /** @var Row $row */
                if ($index < 2) {
                    continue;
                }

                if ($index > 1000) {
                    break;
                }

                $cells = $row->toArray();

                $tglLahir = $cells[4];
                if ($tglLahir instanceof \DateTime) {
                    $tglLahir = $tglLahir->format('Y-m-d');
                }

                $params = [
                    'email'     => trim($cells[1]),
                    'nama'      => trim($cells[2]),
                    'tmp_lahir' => trim($cells[3]),
                    'tgl_lahir' => $tglLahir,
                    'k_group'   => $kGroup,
                ];

                $validator = \Validator::make($params, $rules);
                if ($validator->fails()) {
                    $errors[$index] = "Baris $index: " . implode("; ", $validator->getMessageBag()->all());
                } else {
                    $batches[] = new CreateAkunPengajar($admin, $instansi, array_filter($params), $kGroup);
                    $data[]    = $params;

                    $unique = $params['email'];
                    if (isset($uniques[$unique])) {
                        $duplikat = $uniques[$unique];
                        $errors[$index] = "Baris $index: {$unique} sudah ada di Baris {$duplikat}";
                    } else {
                        $uniques[$unique] = $index;
                    }
                }
            }
        }

        $reader->close();


        if (!$batches) {
            throw new FlowException("Berkas tidak memuat data akun, silakan cek kesesuaian dengan template");
        }


        if ($errors) {
            return [
                'errors' => $errors,
            ];
        }

        $batch = Bus::batch($batches)->dispatch();


        return [
            'batch' => $batch,
            'data'  => $data,
        ];
    }
}
