<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;
use App\Reading;
use App\Alert;

class AlertsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
    	//get all readings sorted by latest reading on top
    	$allAlerts = Alert::all();
        $alerts = $allAlerts->reject(function ($item) {
            return $item->action_taken == 'Resolved';
        })->groupBy('reading_id');

    	return view('alerts', compact('alerts', 'allAlerts'));
    }

  
    public function update($id)
    {
        // validation already handled using this: http://laravel.com/docs/5.0/validation#form-request-validation
        $alert = Alert::find($id);

        $alert->action_taken = Input::get('action_taken');
 
        $alert->save();

        //Session::flash('flash_message', 'User successfully updated!');

        return redirect()->back();
    }
}
