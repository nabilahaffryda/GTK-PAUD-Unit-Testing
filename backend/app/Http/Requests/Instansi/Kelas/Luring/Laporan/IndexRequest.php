<?php

namespace App\Http\Requests\Instansi\Kelas\Luring\Laporan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read int $tahun
 * @property-read int $angkatan
 * @property-read array $filter
 * @property-read string $keyword
 * @property-read int $count
 * @property-read int $page
 */
class IndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tahun'                        => ['nullable', 'int'],
            'angkatan'                     => ['nullable', 'int'],
            'filter'                       => ['nullable', 'array'],
            'filter.laporan_k_verval_paud' => ['nullable', 'integer', 'exists:m_verval_paud,k_verval_paud'],
            'filter.tgl_mulai'             => ['nullable', 'date_format:Y-m-d'],
            'filter.tgl_selesai'           => ['nullable', 'date_format:Y-m-d'],
            'keyword'                      => ['nullable', 'string', 'min:3', 'max:100'],
            'count'                        => ['integer', 'max:50'],
            'page'                         => ['integer', 'min:1'],
        ];
    }
}
