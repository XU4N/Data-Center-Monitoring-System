<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Zone;
use App\Parameter;
use App\Reading;
use Validator;
use Input;
use Excel;

class ReportsController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}

	public function index() {
		$zones = Zone::all();
		$parameters = Parameter::all();

		$current_zone = NULL;
		return view('reports', compact('zones', 'parameters', 'readings', 'current_zone'));
	}

	public function search() {
		$rules = array(
			'zone' => 'required',
			'sensor' => 'required',
			'start_date' => 'required|date_format:Y-m-d',
			'end_date' => 'required|date_format:Y-m-d',
		);
		   
		$v = Validator::make( Input::all(), $rules );

		if ($v->fails()){

			return redirect('reports') ->withErrors($v) ->withInput();
		}
	
		$zones = Zone::all();
		$parameters = Parameter::all();

		$current_zone = Zone::find(Input::get('zone'));
		$current_readings = Reading::where('parameter_id',Input::get('sensor'))
							->where('created_at','>=',Input::get('start_date'))
							->where('created_at','<=',Input::get('end_date'))
							->get();
		return view('reports', compact('zones', 'parameters', 'readings', 'current_zone','current_readings'));
	}

	public function export() {
		$rules = array(
			'zone' => 'required',
			'sensor' => 'required',
			'start_date' => 'required|date_format:Y-m-d',
			'end_date' => 'required|date_format:Y-m-d',
		);
		   
		$v = Validator::make( Input::all(), $rules );

		if ($v->fails()){

			return redirect('reports') ->withErrors($v) ->withInput();
		}
	
		$zones = Zone::all();
		$parameters = Parameter::all();

		$current_zone = Zone::find(Input::get('zone'));
		$current_readings = Reading::where('parameter_id',Input::get('sensor'))
							->where('created_at','>=',Input::get('start_date'))
							->where('created_at','<=',Input::get('end_date'))
							->get();
		
		$start_date = Input::get('start_date');
		$end_date = Input::get('end_date');
		$fileName = $current_zone->zone_name . " readings from " . $start_date . " to " . $end_date;
		$parameter = Parameter::find(Input::get('sensor'))->parameter_name;

		Excel::create($fileName, function($excel) use($parameter, $current_readings){

		    $excel->sheet($parameter, function($sheet) use($current_readings) {

		        $sheet->fromModel($current_readings);

		    });

		})->export('xlsx');
	}
}