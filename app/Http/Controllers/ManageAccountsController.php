<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Log;
use Auth;

class ManageAccountsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');
    }
	public function index() 
	{
		$users = User::withTrashed()->get();
		$roles = Role::all();

		return view('manage_accounts', compact('users', 'roles'));
	}

	 public function store(UserRequest $request)
    {
        // validation already handled using this: http://laravel.com/docs/5.0/validation#form-request-validation
        $user = new User;
            
        $user->name     = Input::get('name');
        $user->email    = Input::get('email');
        $user->password = Input::get('password');
        $user->mobile   = Input::get('mobile');
        $user->role_id  = Input::get('role_id');

        // save our user
        $user->save();

        Session::flash('flash_message', 'User successfully added!');

        return redirect()->back();

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

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        Session::flash('flash_message', 'User successfully deactivated!');

        return redirect()->back();
    }
}


