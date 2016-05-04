<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\CreateThresholdRequest;
use App\Http\Requests\EditThresholdRequest;
use App\Threshold;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ThresholdController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
    	$thresholds = Threshold::all();

    	return view('thresholds', compact('thresholds'));
    }

    public function store(CreateThresholdRequest $request)
    {
        Threshold::create($request->all());

        \Session::flash('flash_message', 'New threshold has been add succesfully!');

    	return redirect()->back();
    }

    public function update(EditThresholdRequest $request, $id)
    {
        $threshold = Threshold::find($id);

        $threshold->min_critical_value    = Input::get('min_critical_value');
        $threshold->min_warning_value   = Input::get('min_warning_value');
        $threshold->normal_value = Input::get('normal_value');
        $threshold->max_warning_value  = Input::get('max_warning_value');
        $threshold->max_critical_value = Input::get('max_critical_value');

        $threshold->save();

        \Session::flash('flash_message', 'Threshold value has been edited successfully!');

        return redirect()->back();
    }
}
