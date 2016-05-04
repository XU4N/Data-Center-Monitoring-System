<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Threshold;
use App\IntelligentPredict;

class IntelligentPredictionController extends Controller
{
    public function index() {
        $readings = collect([22.752, 20.413, 18.624]);

    	$default_monitoring_period = IntelligentPredict::getMonitoringPeriod();
    	
    	$intelligentPredict = new IntelligentPredict();
    	$default_temperature = $intelligentPredict->getDefaultTemperature();
    	$default_offset = $intelligentPredict->getOffset();
    	$monitoring_period_description = $intelligentPredict->getMonitoringPeriodInWords();
    	$readings_for_the_period = IntelligentPredict::prepareReadings(1); //Zone 1;
    	$calculatedTrend = $intelligentPredict->calculateTrend($readings);
    	$feedback = $intelligentPredict->getFeedbackMessage($readings);
    	$temperature_delta = $intelligentPredict->calculateDelta($readings);
        $legend = $intelligentPredict->getMonitoringPeriodLegend();

    	//display the settings page
    	return view('intelligent', 
    		compact('default_temperature', 'default_offset', 'default_monitoring_period', 'monitoring_period_description', 'readings_for_the_period', 'feedback', 'temperature_delta', 'legend', 'readings', 'calculatedTrend'));

    }
}
