<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateThresholdRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'threshold_category' => 'required',
            'min_critical_value' => 'required',
            'min_warning_value' => 'required',
            'normal_value' => 'required',
            'max_warning_value' => 'required',
            'max_critical_value' => 'required'
        ];
    }
}
