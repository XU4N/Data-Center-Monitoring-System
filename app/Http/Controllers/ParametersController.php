<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Parameter;
use App\Zone;
use App\Threshold;
use App\Http\Requests\ParameterRequest;
use Request;
use Illuminate\Support\Facades\Session;

class ParametersController extends Controller
{

	public function __construct() {
		$this->middleware('auth');
	}

    public function index() {
        $parameters = Parameter::all();
        $zones = Zone::all();
        $thresholds = Threshold::all();
        
        return view('parameters', compact('parameters', 'zones', 'thresholds'));
    }

    public function store(ParameterRequest $request) {
        $input = Request::all();

        Parameter::create($input);

        Session::flash('flash_message', 'Parameter successfully added!');

        return redirect('parameters');
    }

    public function update(ParameterRequest $request, $id) {
        $input = Request::all();
        $parameter = Parameter::findOrFail($id);
        $parameter->update($input);

        Session::flash('flash_message', 'Parameter successfully updated!');

        return redirect('parameters');
    }

    public function destroy($id) {
        $parameter = Parameter::findOrFail($id);
        $parameter->delete();

        Session::flash('flash_message', 'Parameter successfully deleted!');

        return redirect('parameters');
    }

}
