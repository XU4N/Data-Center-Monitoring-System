<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;

class ProfileController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
	public function index() 
	{
		$roles = Role::all();

		return view('profile', compact('roles'));
	}

	 public function update(UserRequest $request, $id)
    {
        // validation already handled using this: http://laravel.com/docs/5.0/validation#form-request-validation
        $user = User::find($id);

        $user->name     = Input::get('name');
        $user->email    = Input::get('email');
        $user->password = Input::get('password');
        $user->mobile   = Input::get('mobile');
        $user->role_id  = Input::get('role_id');

        $user->save();

        Session::flash('flash_message', 'User successfully updated!');

        return redirect()->back();
    }
}
