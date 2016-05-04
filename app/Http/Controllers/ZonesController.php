<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ZoneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Zone;
use App\Alert;

class ZonesController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

	public function index() 
	{
		$zones = Zone::all();

		return view('zones', compact('zones'));
	}

	public function store(ZoneRequest $request)
    {
        // validation already handled using this: http://laravel.com/docs/5.0/validation#form-request-validation
        $zone = new Zone;
            
        $zone->zone_name = Input::get('zone_name');

        $zone->save();

        Session::flash('flash_message', 'Zone successfully added!');

        return redirect()->back();
    }

    public function update(ZoneRequest $request, $id)
    {
        // validation already handled using this: http://laravel.com/docs/5.0/validation#form-request-validation
        $zone = Zone::find($id);

        $zone->zone_name = Input::get('zone_name');

        $zone->save();

        Session::flash('flash_message', 'Zone successfully updated!');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $zone = Zone::find($id);

        $zone->delete();

        Session::flash('flash_message', 'Zone successfully deleted!');

        return redirect()->back();
    }
}