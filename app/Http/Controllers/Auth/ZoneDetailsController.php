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

class ZoneDetailsController extends Controller
{
	public function __construct() 
	{
		$this->middleware('auth');
	}

    public function index($zone_id) // accept the zone id and then start displaying the relevant information
    {
        $zone = Zone::find($zone_id);
        $parameters = Parameter::where('zone_id', $zone_id)->get();

        // get records which are 7 days ago (one week)
        $alerts = Alert::whereBetween('created_at', array(Carbon\Carbon::tomorrow()->subWeek(), Carbon\Carbon::now()) )->get();
        $readings = Reading::whereBetween('created_at', array(Carbon\Carbon::tomorrow()->subWeek(), Carbon\Carbon::now()) )->get();

        // filter the alerts and readings
        $alerts = $alerts->filter(function ($item) use ($zone_id) // alert details 
        {
            return $item->isInThisZone($zone_id);
        });

        $readings = $readings->filter(function ($item) use ($zone_id) // reading details
        {
            return $item->isInThisZone($zone_id);
        });
        
        // categorize the alerts based on action taken
    	$alertsNeedingAttention = $alerts->filter(function ($item) {

            return $item->requiresAttention();

        })->groupBy('zone_id');

        $alertsInProgress = $alerts->filter(function ($item) {
            
            return $item->isInProgress();

        })->groupBy('zone_id');

        // zone condition status
        if (!$alertsNeedingAttention->isEmpty() && !$alertsInProgress->isEmpty())
            $condition = "Abnormal";
        else
            $condition = "Normal";

        // get the temperature & humidity type paramters id
        $tempParam = new \Illuminate\Database\Eloquent\Collection;
        $humParam = new \Illuminate\Database\Eloquent\Collection;
        
        $tempParam = $zone->getTemperatureTypeParameter();
        $humParam = $zone->getHumidityTypeParameter();

        // get current temperature (average the latest temperature reading records)
        if (!$tempParam->isEmpty())
        {
            $sum = 0;

            foreach ($tempParam as $p)
            {
                $tmp = $readings->filter(function ($item) use ($p)
                {
                    return $item->parameter_id == $p;

                })->sortByDesc('updated_at');

                if (!$tmp->isEmpty()) // check whether there are any reading records
                    $sum += $tmp->first()->getReadingValue();
            }

            $current_temp = round($sum / $tempParam->count(), 2); // rount to at most two precision
        }
        else
            $current_temp = 0;

        // get current humidity (average the latest humidity reading records)
        if (!$humParam->isEmpty())
        {
            $sum = 0;

            foreach ($humParam as $p)
            {
                $tmp = $readings->filter(function ($item) use ($p)
                {
                    return $item->parameter_id == $p;

                })->sortByDesc('updated_at');

                if (!$tmp->isEmpty()) // check whether there are any reading records
                    $sum += $tmp->first()->getReadingValue();
            }

            $current_hum = round($sum / $humParam->count(), 2); // rount to at most two precision
        }
        else
            $current_hum = 0;

        // get temperature chart's data
        $temperatureChartData = array();

        foreach ($tempParam as $p) // loop each 'temperature' type parameter 
        {
            $parameterTemperature = array_fill(0, 7, 0.0);
            
            for ($i = 0; $i < 7; $i++) // loop seven days reading
            {
                $sum = 0; // used to sum up the temperature for each day

                $tmp = $readings->filter(function ($item) use ($i, $p)
                {
                    return ($item->parameter_id == $p) 
                            && ($item->created_at >= Carbon\Carbon::tomorrow()->subWeek()->addDays($i)) 
                            && ($item->created_at <= Carbon\Carbon::tomorrow()->subWeek()->addDays($i + 1));
                });

                // sum the temperature for each day of each parameter
                if (!$tmp->isEmpty())
                {
                    foreach ($tmp as $t)
                        $sum += $t->reading_value;

                    $parameterTemperature[$i] = round($sum / $tmp->count(), 2); // rount to at most two precision
                }
            }

            $temperatureChartData[] = $parameterTemperature;
        }
        
        // get humidity chart's data
        $humidityChartData = array();

        foreach ($humParam as $p) // loop each 'temperature' type parameter 
        {
            $parameterHumidity = array_fill(0, 7, 0.0);
            
            for ($i = 0; $i < 7; $i++) // loop seven days reading
            {
                $sum = 0; // used to sum up the temperature for each day

                $tmp = $readings->filter(function ($item) use ($i, $p)
                {
                    return ($item->parameter_id == $p) 
                            && ($item->created_at >= Carbon\Carbon::tomorrow()->subWeek()->addDays($i)) 
                            && ($item->created_at <= Carbon\Carbon::tomorrow()->subWeek()->addDays($i + 1));
                });

                // sum the temperature for each day of each parameter
                if (!$tmp->isEmpty())
                {
                    foreach ($tmp as $t)
                        $sum += $t->reading_value;

                    $parameterHumidity[$i] = round($sum / $tmp->count(), 2); // rount to at most two precision
                }
            }

            $humidityChartData[] = $parameterHumidity;
        }

        // get warning & critical alert count, and alert chart's data
        $warning = 0;
        $critical = 0;
        $alertChartWarningData = array_fill(0, 7, 0);
        $alertChartCriticalData = array_fill(0, 7, 0);

        for ($i = 0; $i < 7; $i++) // loop seven days data
        {
            $tmp = $alerts->filter(function ($item) use ($i)
            {
                return ($item->created_at >= Carbon\Carbon::tomorrow()->subWeek()->addDays($i)) 
                        && ($item->created_at <= Carbon\Carbon::tomorrow()->subWeek()->addDays($i + 1));
            });

            foreach ($tmp as $t)
            {
                // check whether the alert is warning or critical (based on the first word in description)
                $aa = explode(' ', trim($t->alert_description));

                if ($aa[0] == "Warning")
                {
                    $warning++;
                    $alertChartWarningData[$i]++;
                }
                else
                {
                    $critical++;
                    $alertChartCriticalData[$i]++;
                }
            }
        }
        
        // set a carbon date
        $date = Carbon\Carbon::today();
        $date->subWeek(); // or $date->subDays(7)

        // set chart's label (date will be added until today)
        $labels = array();

        for ($i = 0; $i < 7; $i++)
        {
            $formattedDate = $date->addDays(1)->format('M d');
            array_push($labels, "\"".$formattedDate."\"");
        }        

    	return view('zone_details', compact('zone', 'parameters', 'alerts', 'readings', 'condition', 'current_temp', 'current_hum', 'tempParam', 'humParam', 'labels', 'temperatureChartData', 'humidityChartData', 'alertChartWarningData', 'alertChartCriticalData', 'warning', 'critical'));
    }

    // get database storage summary ??
}