<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use App\Http\Requests\Request;

class UserRequest extends Request
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
        'name'             => 'required',                        // just a normal required validation
        'email'            => 'required|email',                  // required and must be unique in the user table
        'password'         => 'required|min:8|alpha_num',
        'password_confirm' => 'required|same:password',          // required and has to match the password field
        'mobile'           => 'required', 
        'role_id'          => 'required'
        ];
    }
}
