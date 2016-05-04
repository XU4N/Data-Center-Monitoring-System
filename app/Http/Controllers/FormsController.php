<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// use App\Http\Requests;
use App\Events\AbnormalReadingWasTaken;
use App\Http\Controllers\Controller;
use App\Zone;
use App\Parameter;
use App\Reading;
use Event;
use Request;
use Input;
use Validator;
use Auth;

class FormsController extends Controller
{

public function __construct() {
    $this->middleware('auth');
}

public function index() {
	$filter = 1;

	$zones = Zone::all();
	$parameters = Parameter::ofZone($filter)->get();

	return view('forms', compact('zones', 'parameters'));
}

    public function store() {
        // TODO: The reading value can still be an empty reading. Resolve this error.

        $rules = array(
            'readings' => 'required',
        );

        $v = Validator::make( Input::all(), $rules );

        if ($v->fails()) {   
            return array('error'=>true, 'message' => 'Validation Failed. Make sure you\'ve filled in all the readings on this form');
        } else {

            $readings = json_decode(Input::get('readings'),TRUE);
            foreach ($readings as $reading) {
                $r = new Reading();
                $r->parameter_id = $reading['parameter_id'];
                $r->reading_value = $reading['reading_value'];
                $r->user_id = Auth::user()->id;
                if ($result = $r->save()) {
                    if ($r->readingStatus() != 'Normal') {
                        //fire abnormal rea
                            \Event::fire(new AbnormalReadingWasTaken($r));
                    }
                }

            }

                if($result) {
                    return array('error'=>false, 'message' => 'Reading Recorded');

                 } else {
                     return array('error'=>true, 'message' => 'An error occured. Please try again');
                 }

        }
    }

    public function event() {
        $my_reading = new Reading();
        $reading = Reading::all()->last();
        $my_reading->parameter_id = $reading->parameter_id;
        $my_reading->reading_value = $reading->reading_value;
        $my_reading->user_id = $reading->user_id;

        \Event::fire(new AbnormalReadingWasTaken($reading));
    }

    public function readSensorData(){
        $url = 'https://data.sparkfun.com/output/LQL4w78xVwSmbGoapmj3.json';
        $json_data = file_get_contents($url);
        $data = json_decode($json_data, true);
         
        //schedule to take readings at every morning at 10 and evening at 3pm  
        $count = array(); 
        $temperature = array();
        $humidity = array();
        $i = 1;
        foreach($data as $reading) {
            array_push($count, $i);
            array_push($temperature, $reading['temperature']);
            array_push($humidity, $reading['humidity']);
            $i++;

            if ($i == 11)
                break;
        }
        // dd($temperature);
        // dd($humidity);
        // dd($data);
        return view('sensor', compact('data' ,'temperature', 'humidity', 'count'));
    }
}