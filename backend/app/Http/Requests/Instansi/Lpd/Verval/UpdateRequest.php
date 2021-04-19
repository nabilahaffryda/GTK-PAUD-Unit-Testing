<?php

namespace App\Http\Requests\Instansi\Lpd\Verval;

use App\Exceptions\FlowException;
use App\Models\MVervalPaud;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @property-read $k_verval_paud
 * @package App\Http\Requests\Instansi\Lpd\Verval
 */
class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mVervalPaud = MVervalPaud::find($this->k_verval_paud);
        if (!$mVervalPaud) {
            throw new FlowException("Jenis Verval tidak valid");
        }
        return [
            'k_verval_paud' => ['required', 'integer'],
        ];
    }
}
