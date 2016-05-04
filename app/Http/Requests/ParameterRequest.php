<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests\Request;

class ParameterRequest extends Request
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
        // create the validation rules ------------------------

        return [
        'parameter_name'             => 'required',                  // just a normal required validation
        'parameter_description'      => 'required',                  // description required in the parameter table
        ];
    }
}
