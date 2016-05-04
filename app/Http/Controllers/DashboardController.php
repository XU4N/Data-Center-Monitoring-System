<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Zone;
use App\Parameter;
use App\Alert;
use App\Reading;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index() {
        $zones = Zone::all();
        $alerts = Alert::all();
        $parameters = Parameter::all();
        $readings = Reading::whereBetween('created_at', array(Carbon\Carbon::tomorrow()->subWeek(), Carbon\Carbon::now()) )->get(); // get all readings seven day ago
        
        $alertsNeedingAttention = $alerts->filter(function ($item) {

            return $item->requiresAttention();

        })->groupBy('zone_id');

        $alertsInProgress = $alerts->filter(function ($item) {
            
            return $item->isInProgress();

        })->groupBy('zone_id');

        // Purpose: to get the average temperature of each zone (average the latest reading from all 'temperature' type parameters)
        $param = new \Illuminate\Database\Eloquent\Collection;
        $current_temp = array();

        foreach($zones as $zone) 
        {
            $param = ($zone->getTemperatureTypeParameter());

            if (!$param->isEmpty()) // check whether the zone has any paramter with temperature type
            {
                $sum = 0;
                
                foreach ($param as $p)
                {
                    $tmp = $readings->filter(function ($item) use($p){
                        
                        return $item->parameter_id == $p;

                    })->sortByDesc('updated_at'); // filter and sort the reading records

                    if (!$tmp->isEmpty()) // check whether there are any reading records
                        $sum += $tmp->first()->getReadingValue();
                }

                $current_temp[] = round($sum / $param->count(), 2); // round to at most two precision
            }
            else
                $current_temp[] = 0;
        }
        
        return view('dashboard', compact('zones', 'parameters', 'alertsNeedingAttention', 'alertsInProgress', 'current_temp'));
    }
}
