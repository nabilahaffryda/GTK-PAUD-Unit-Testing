<?php

namespace App\Http\Requests;

/**
 * @property $name
 * @property $column
 * @property $filter
 */
class MasterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => ['array', 'required', 'min:1'],
            'name.*'     => ['string', 'required'],
            'column'     => ['array', 'sometimes', 'nullable'],
            'column.*'   => ['array', 'required'],
            'column.*.*' => ['string', 'required'],
            'filter'     => ['array', 'sometimes', 'nullable'],
            'filter.*'   => ['array', 'required'],
        ];
    }
}
