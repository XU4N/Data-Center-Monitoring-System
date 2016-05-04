<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Reading;
use Request;

class ReadingsController extends Controller
{

    public function test() {
        $readings = Reading::ofReadings()->get();
        dd($readings);
    }

	public function __construct() {
		$this->middleware('auth');
	}

    public function index() {
    	$readings = Reading::all();
        $records = Reading::ofReadings()->get();

    	
    	return view('readings', compact('readings', 'records'));
    }

    public function destroy($id) {
        $reading = Reading::findOrFail($id);
        $reading->delete();

        Session::flash('flash_message', 'Reading successfully deleted!');

        return redirect('readings');
    }
}
