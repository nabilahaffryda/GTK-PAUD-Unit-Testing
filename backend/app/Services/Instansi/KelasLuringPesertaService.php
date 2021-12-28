<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\MInstrumenNilaiLuringPaud;
use App\Models\MTahapNilaiLuringPaud;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Models\PaudKelasPesertaLuringNilai;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelasLuringPesertaService
{
    public function index(PaudKelasLuring $kelas, array $params): HasMany
    {
        return $kelas
            ->paudKelasPesertaLurings()
            ->when($params['keyword'] ?? null, function ($query, $value) {
                $query->where(function ($query) use ($value) {
                    $query
                        ->orWhereExists(function ($query) use ($value) {
                            $query
                                ->select(DB::raw(1))
                                ->from('ptk')
                                ->whereColumn('paud_kelas_peserta_luring.ptk_id', 'ptk.ptk_id')
                                ->where('ptk.nama', 'like', "%{$value}%");
                        })
                        ->orWhereExists(function (Builder $query) use ($value) {
                            $query
                                ->select(DB::raw(1))
                                ->from('paud_peserta_nonptk')
                                ->whereColumn('paud_kelas_peserta_luring.paud_peserta_nonptk_id', '=', 'paud_peserta_nonptk.paud_peserta_nonptk_id')
                                ->where('paud_peserta_nonptk.nama', 'like', "%{$value}%");
                        });
                });
            });
    }

    public function listNilai(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta, bool $isPpm)
    {
        $mInstruments = MInstrumenNilaiLuringPaud::query()
            ->when($isPpm, function (Builder $query) {
                $query->where('k_tahap_nilai_luring_paud', MTahapNilaiLuringPaud::PENDALAMAN_MATERI);
            }, function (Builder $query) {
                $query->where('k_tahap_nilai_luring_paud', MTahapNilaiLuringPaud::TUGAS_MANDIRI);
            })
            ->orderBy('urutan')
            ->get()
            ->keyBy('k_instrumen_nilai_luring_paud');

        $nilais = $peserta->paudKelasPesertaLuringNilais()
            ->whereIn('k_instrumen_nilai_luring_paud', $mInstruments->keys())
            ->get()
            ->keyBy('k_instrumen_nilai_luring_paud');

        $results = [];
        foreach ($mInstruments as $kInstrumen => $mInstrument) {
            $results[] = $nilais->pull($kInstrumen, fn() => PaudKelasPesertaLuringNilai::make([
                'paud_kelas_peserta_luring_id'  => $kelas->paud_kelas_luring_id,
                'k_instrumen_nilai_luring_paud' => $kInstrumen,
            ]));
        }

        return PaudKelasPesertaLuringNilai::make()->newCollection($results);
    }

    /**
     * @throws FlowException
     */
    public function saveNilai(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta, array $nilais, bool $isPpm)
    {
        /** @var array<int, MInstrumenNilaiLuringPaud>|Collection $mInstruments */
        $mInstruments = MInstrumenNilaiLuringPaud::query()
            ->when($isPpm, function (Builder $query) {
                $query->where('k_tahap_nilai_luring_paud', MTahapNilaiLuringPaud::PENDALAMAN_MATERI);
            }, function (Builder $query) {
                $query->where('k_tahap_nilai_luring_paud', MTahapNilaiLuringPaud::TUGAS_MANDIRI);
            })
            ->orderBy('urutan')
            ->get()
            ->keyBy('k_instrumen_nilai_luring_paud');

        $newNilais = $nilais;
        if (array_diff(array_keys($newNilais), $mInstruments->keys()->all())) {
            throw new FlowException('Ada Instrumen nilai yang tidak dikenali');
        }

        $nilais = $peserta->paudKelasPesertaLuringNilais()
            ->whereIn('k_instrumen_nilai_luring_paud', $mInstruments->keys())
            ->exists();

        if ($nilais) {
            throw new FlowException('Peserta sudah dinilai');
        }

        $total   = 0;
        $results = PaudKelasPesertaLuringNilai::make()->newCollection();
        foreach ($mInstruments as $kInstrumen => $mInstrument) {
            /** @var PaudKelasPesertaLuringNilai $nilai */
            $nilai = PaudKelasPesertaLuringNilai::create([
                'paud_kelas_peserta_luring_id'  => $kelas->paud_kelas_luring_id,
                'k_instrumen_nilai_luring_paud' => $kInstrumen,
                'nilai'                         => $newNilais[$kInstrumen] ?? 0,
            ]);

            $total += $nilai->nilai * $mInstrument->n_bobot / 100;

            $results[] = $nilai;
        }

        if ($isPpm) {
            $peserta->n_pendalaman_materi = min(round($total, 2), 100);
        } else {
            $peserta->n_tugas_mandiri = min(round($total, 2), 100);
        }

        $peserta->nilai = $peserta->n_pendalaman_materi * 0.4 + $peserta->n_tugas_mandiri * 0.6;
        $peserta->save();

        return $results;
    }

    public function deleteNilai(PaudKelasLuring $kelas, PaudKelasPesertaLuring $peserta, bool $isPpm)
    {
        $nilais = $peserta->paudKelasPesertaLuringNilais()
            ->whereHas('mInstrumenNilaiLuringPaud', function (Builder $query) use ($isPpm) {
                $query->when($isPpm, function (Builder $query) {
                    $query->where('k_tahap_nilai_luring_paud', MTahapNilaiLuringPaud::PENDALAMAN_MATERI);
                }, function (Builder $query) {
                    $query->where('k_tahap_nilai_luring_paud', MTahapNilaiLuringPaud::TUGAS_MANDIRI);
                });
            })
            ->get();

        foreach ($nilais as $nilai) {
            $nilai->delete();
        }

        if ($isPpm) {
            $peserta->n_pendalaman_materi = 0;
        } else {
            $peserta->n_tugas_mandiri = 0;
        }

        $peserta->nilai = $peserta->n_pendalaman_materi * 0.4 + $peserta->n_tugas_mandiri * 0.6;
        $peserta->save();

        return $this->listNilai($kelas, $peserta, $isPpm);
    }
}
