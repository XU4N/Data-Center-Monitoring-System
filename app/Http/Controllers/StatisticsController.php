<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Zone;
use App\Parameter;
use App\Reading;
use App\Alert;
use Validator;
use Input;

class StatisticsController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
	}
	
    public function index() 
    {
    	$zones = Zone::all();
    	$parameters = Parameter::all();

    	$current_zone = NULL;
		return view('statistics', compact('zones', 'parameters', 'readings', 'current_zone'));
    }

    public function view() 
    {
		$rules = array
		(
			'zone' => 'required',
			'sensor' => 'required',
			'year' => 'required',
			'month' => 'required',
			'type' => 'required',
			'particular' => 'required'
		);
		   
		$v = Validator::make( Input::all(), $rules );

		if ($v->fails()){

			return redirect('statistics') ->withErrors($v) ->withInput();
		}
	
		$zones = Zone::all();
		$parameters = Parameter::all();

		// get zone, parameter, type, particular
		$current_zone = Zone::find(Input::get('zone'));
		$current_para = Parameter::find(Input::get('sensor'));
		$current_type = Input::get('type');
		$current_particular = Input::get('particular');

		// get parameter's threshold type (in order to set chart display options)
		$threshold_type = $current_para->getThresholdCategory();
		$threshold_unit = $current_para->getThresholdUnit();

		// get year
		$dateElements = explode('-', Input::get('year'));
		$year = $dateElements[0];

		// get month
		$month = Input::get('month');

		if ($month == "January")
			$m = "01";
		else if ($month == "February")
			$m = "02";
		else if ($month == "March")
			$m = "03";
		else if ($month == "April")
			$m = "04";
		else if ($month == "May")
			$m = "05";
		else if ($month == "June")
			$m = "06";
		else if ($month == "July")
			$m = "07";
		else if ($month == "August")
			$m = "08";
		else if ($month == "September")
			$m = "09";
		else if ($month == "October")
			$m = "10";
		else if ($month == "November")
			$m = "11";
		else if ($month == "December")
			$m = "12";
		else 
			$m = "";

		// set start date & end date & label count
		if ($month == "Annual")
		{
			$start_date = $year.'-01-01';
			$end_date = $year.'-12-31 23:59:59';
			$label_count = 12;
		}
		else // monthly type
		{
			if ($month == "February") // check leap year
			{
				if ($year % 400 == 0)
					$label_count = 29;
				else if ($year % 100 == 0)
					$label_count = 28;
				else if ($year % 4 == 0)
					$label_count = 29;
				else
					$label_count = 28;
			}
			else if ($month == "April" || $month == "June" || $month == "September" || $month == "November") // month with 30days
				$label_count = 30;
			else // 31 days
				$label_count = 31;

			$start_date = $year.'-'.$m.'-01';
			$end_date = $year.'-'.$m.'-31 23:59:59';
		}

		// initialize chart's data
		$chart_data = "";
		$chart_data2 = "";

		// check statistics type
		if ($current_type == "Reading")
		{
			// get readings
			$current_readings = Reading::where('parameter_id',Input::get('sensor'))
							->where('created_at','>=', $start_date)
							->where('created_at','<=', $end_date)
							->get();

			// check readings data
			if (count($current_readings) == 0)
				$display = FALSE;
			else
				$display = TRUE;

			// check statistics particular
			if ($current_particular == "Average")
			{
				// initialize array & variables
				$count = array();
				$total = array();
				$avg = array();

				for ($i = 0; $i < $label_count; $i++)
				{
					$count[] = 0;
					$total[] = 0;
				}	

				// set chart's data
				if ($label_count == 12) // annual statistics
				{
					foreach ($current_readings as $reading)
					{
						if ($reading->created_at->month == 1) // january
						{
							$total[0] += $reading->reading_value;
							$count[0]++;
						}
						else if ($reading->created_at->month == 2) // february
						{
							$total[1] += $reading->reading_value;
							$count[1]++;
						}
						else if ($reading->created_at->month == 3) // march
						{
							$total[2] += $reading->reading_value;
							$count[2]++;
						}
						else if ($reading->created_at->month == 4) // april
						{
							$total[3] += $reading->reading_value;
							$count[3]++;
						}
						else if ($reading->created_at->month == 5) // may
						{
							$total[4] += $reading->reading_value;
							$count[4]++;
						}
						else if ($reading->created_at->month == 6) // june
						{
							$total[5] += $reading->reading_value;
							$count[5]++;
						}
						else if ($reading->created_at->month == 7) // july
						{
							$total[6] += $reading->reading_value;
							$count[6]++;
						}
						else if ($reading->created_at->month == 8) // august
						{
							$total[7] += $reading->reading_value;
							$count[7]++;
						}
						else if ($reading->created_at->month == 9) // september
						{
							$total[8] += $reading->reading_value;
							$count[8]++;
						}
						else if ($reading->created_at->month == 10) // october
						{
							$total[9] += $reading->reading_value;
							$count[9]++;
						}
						else if ($reading->created_at->month == 11) // november
						{
							$total[10] += $reading->reading_value;
							$count[10]++;
						}
						else if ($reading->created_at->month == 12) // december
						{
							$total[11] += $reading->reading_value;
							$count[11]++;
						}
					}

					// get average & set as chart's data
					for ($i = 0; $i < $label_count; $i++)
					{
						if ($count[$i] > 0)
							$avg[$i] = round($total[$i] / $count[$i], 2); // round float number to 2 precision
						else
							$avg[$i] = 0;
					}
					
					$chart_data = "data: [".implode(",", $avg)."]";
				}
				else // monthly statistics with 28, 29, 30, 31 days
				{
					foreach ($current_readings as $reading)
					{
						if ($reading->created_at->day == 1) // 1st
						{
							$total[0] += $reading->reading_value;
							$count[0]++;
						}
						else if ($reading->created_at->day == 2) // 2nd
						{
							$total[1] += $reading->reading_value;
							$count[1]++;
						}
						else if ($reading->created_at->day == 3) // 3rd
						{
							$total[2] += $reading->reading_value;
							$count[2]++;
						}
						else if ($reading->created_at->day == 4) // 4th
						{
							$total[3] += $reading->reading_value;
							$count[3]++;
						}
						else if ($reading->created_at->day == 5) // 5th
						{
							$total[4] += $reading->reading_value;
							$count[4]++;
						}
						else if ($reading->created_at->day == 6) // 6th
						{
							$total[5] += $reading->reading_value;
							$count[5]++;
						}
						else if ($reading->created_at->day == 7) // 7th
						{
							$total[6] += $reading->reading_value;
							$count[6]++;
						}
						else if ($reading->created_at->day == 8) // 8th
						{
							$total[7] += $reading->reading_value;
							$count[7]++;
						}
						else if ($reading->created_at->day == 9) // 9th
						{
							$total[8] += $reading->reading_value;
							$count[8]++;
						}
						else if ($reading->created_at->day == 10) // 10th
						{
							$total[9] += $reading->reading_value;
							$count[9]++;
						}
						else if ($reading->created_at->day == 11) // 11th
						{
							$total[10] += $reading->reading_value;
							$count[10]++;
						}
						else if ($reading->created_at->day == 12) // 12th
						{
							$total[11] += $reading->reading_value;
							$count[11]++;
						}
						else if ($reading->created_at->day == 13) // 13th
						{
							$total[12] += $reading->reading_value;
							$count[12]++;
						}
						else if ($reading->created_at->day == 14) // 14th
						{
							$total[13] += $reading->reading_value;
							$count[13]++;
						}
						else if ($reading->created_at->day == 15) // 15th
						{
							$total[14] += $reading->reading_value;
							$count[14]++;
						}
						else if ($reading->created_at->day == 16) // 16th
						{
							$total[15] += $reading->reading_value;
							$count[15]++;
						}
						else if ($reading->created_at->day == 17) // 17th
						{
							$total[16] += $reading->reading_value;
							$count[16]++;
						}
						else if ($reading->created_at->day == 18) // 18th
						{
							$total[17] += $reading->reading_value;
							$count[17]++;
						}
						else if ($reading->created_at->day == 19) // 19th
						{
							$total[18] += $reading->reading_value;
							$count[18]++;
						}
						else if ($reading->created_at->day == 20) // 20th
						{
							$total[19] += $reading->reading_value;
							$count[19]++;
						}
						else if ($reading->created_at->day == 21) // 21th
						{
							$total[20] += $reading->reading_value;
							$count[20]++;
						}
						else if ($reading->created_at->day == 22) // 22th
						{
							$total[21] += $reading->reading_value;
							$count[21]++;
						}
						else if ($reading->created_at->day == 23) // 23th
						{
							$total[22] += $reading->reading_value;
							$count[22]++;
						}
						else if ($reading->created_at->day == 24) // 24th
						{
							$total[23] += $reading->reading_value;
							$count[23]++;
						}
						else if ($reading->created_at->day == 25) // 25th
						{
							$total[24] += $reading->reading_value;
							$count[24]++;
						}
						else if ($reading->created_at->day == 26) // 26th
						{
							$total[25] += $reading->reading_value;
							$count[25]++;
						}
						else if ($reading->created_at->day == 27) // 27th
						{
							$total[26] += $reading->reading_value;
							$count[26]++;
						}
						else if ($reading->created_at->day == 28) // 28th
						{
							$total[27] += $reading->reading_value;
							$count[27]++;
						}
						else if ($reading->created_at->day == 29) // 29th
						{
							$total[28] += $reading->reading_value;
							$count[28]++;
						}
						else if ($reading->created_at->day == 30) // 30th
						{
							$total[29] += $reading->reading_value;
							$count[29]++;
						}
						else if ($reading->created_at->day == 31) // 31th
						{
							$total[30] += $reading->reading_value;
							$count[30]++;
						}
					}

					// get average & set as chart's data
					for ($i = 0; $i < $label_count; $i++)
					{
						if ($count[$i] > 0)
							$avg[$i] = round($total[$i] / $count[$i], 2); // round float number to 2 precision
						else
							$avg[$i] = 0;
					}
					
					$chart_data = "data: [".implode(",", $avg)."]";
				}
			}
			// if morning & evening comparison
			else
			{
				// initialize array & variables
				$morning = array();
				$evening = array();

				for ($i = 0; $i < $label_count; $i++)
				{
					$morning[] = 0;
					$evening[] = 0;
				}

				// set chart's data
				if ($label_count == 12) // annual statistics
				{
					// initialize array & variables
					$mtotal = array();
					$etotal = array();
					$mcount = array();
					$ecount = array();
					$mavg = array();
					$eavg = array();

					for ($i = 0; $i < $label_count; $i++)
					{
						$mtotal[] = 0;
						$etotal[] = 0;
						$mcount[] = 0;
						$ecount[] = 0;
					}

					foreach ($current_readings as $reading)
					{
						if ($reading->created_at->month == 1) // january
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[0] += $reading->reading_value;
								$mcount[0]++; 
							}
							else // after 12pm
							{
								$etotal[0] += $reading->reading_value;
								$ecount[0]++; 
							}
						}
						else if ($reading->created_at->month == 2) // february
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[1] += $reading->reading_value;
								$mcount[1]++; 
							}
							else // after 12pm
							{
								$etotal[1] += $reading->reading_value;
								$ecount[1]++; 
							}
						}
						else if ($reading->created_at->month == 3) // march
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[2] += $reading->reading_value;
								$mcount[2]++; 
							}
							else // after 12pm
							{
								$etotal[2] += $reading->reading_value;
								$ecount[2]++; 
							}
						}
						else if ($reading->created_at->month == 4) // april
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[3] += $reading->reading_value;
								$mcount[3]++; 
							}
							else // after 12pm
							{
								$etotal[3] += $reading->reading_value;
								$ecount[3]++; 
							}
						}
						else if ($reading->created_at->month == 5) // may
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[4] += $reading->reading_value;
								$mcount[4]++; 
							}
							else // after 12pm
							{
								$etotal[4] += $reading->reading_value;
								$ecount[4]++; 
							}
						}
						else if ($reading->created_at->month == 6) // june
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[5] += $reading->reading_value;
								$mcount[5]++; 
							}
							else // after 12pm
							{
								$etotal[5] += $reading->reading_value;
								$ecount[5]++; 
							}
						}
						else if ($reading->created_at->month == 7) // july
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[6] += $reading->reading_value;
								$mcount[6]++; 
							}
							else // after 12pm
							{
								$etotal[6] += $reading->reading_value;
								$ecount[6]++; 
							}
						}
						else if ($reading->created_at->month == 8) // august
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[7] += $reading->reading_value;
								$mcount[7]++; 
							}
							else // after 12pm
							{
								$etotal[7] += $reading->reading_value;
								$ecount[7]++; 
							}
						}
						else if ($reading->created_at->month == 9) // september
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[8] += $reading->reading_value;
								$mcount[8]++; 
							}
							else // after 12pm
							{
								$etotal[8] += $reading->reading_value;
								$ecount[8]++; 
							}
						}
						else if ($reading->created_at->month == 10) // october
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[9] += $reading->reading_value;
								$mcount[9]++; 
							}
							else // after 12pm
							{
								$etotal[9] += $reading->reading_value;
								$ecount[9]++; 
							}
						}
						else if ($reading->created_at->month == 11) // november
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[10] += $reading->reading_value;
								$mcount[10]++; 
							}
							else // after 12pm
							{
								$etotal[10] += $reading->reading_value;
								$ecount[10]++; 
							}
						}
						else if ($reading->created_at->month == 12) // december
						{
							if ($reading->created_at->hour <= 12) // morning before 12pm
							{
								$mtotal[11] += $reading->reading_value;
								$mcount[11]++; 
							}
							else // after 12pm
							{
								$etotal[11] += $reading->reading_value;
								$ecount[11]++; 
							}
						}
					}

					// get average & set as chart's data
					for ($i = 0; $i < $label_count; $i++)
					{
						// morning average
						if ($mcount[$i] > 0)
							$mavg[$i] = round($mtotal[$i] / $mcount[$i], 2); // round float number to 2 precision
						else
							$mavg[$i] = 0;

						// evening average
						if ($ecount[$i] > 0)
							$eavg[$i] = round($etotal[$i] / $ecount[$i], 2); // round float number to 2 precision
						else
							$eavg[$i] = 0;
					}
					
					// set chart's data
					$chart_data = "data: [".implode(",", $mavg)."]";
					$chart_data2 = "data: [".implode(",", $eavg)."]";
				}
				else // monthly statistics with 28, 29, 30, 31 days
				{
					foreach ($current_readings as $reading)
					{
						if ($reading->created_at->day == 1) // 1st
						{
							if ($reading->created_at->hour <= 12)
								$morning[0] = $reading->reading_value;
							else
								$evening[0] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 2) // 2nd
						{
							if ($reading->created_at->hour <= 12)
								$morning[1] = $reading->reading_value;
							else
								$evening[1] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 3) // 3rd
						{
							if ($reading->created_at->hour <= 12)
								$morning[2] = $reading->reading_value;
							else
								$evening[2] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 4) // 4th
						{
							if ($reading->created_at->hour <= 12)
								$morning[3] = $reading->reading_value;
							else
								$evening[3] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 5) // 5th
						{
							if ($reading->created_at->hour <= 12)
								$morning[4] = $reading->reading_value;
							else
								$evening[4] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 6) // 6th
						{
							if ($reading->created_at->hour <= 12)
								$morning[5] = $reading->reading_value;
							else
								$evening[5] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 7) // 7th
						{
							if ($reading->created_at->hour <= 12)
								$morning[6] = $reading->reading_value;
							else
								$evening[6] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 8) // 8th
						{
							if ($reading->created_at->hour <= 12)
								$morning[7] = $reading->reading_value;
							else
								$evening[7] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 9) // 9th
						{
							if ($reading->created_at->hour <= 12)
								$morning[8] = $reading->reading_value;
							else
								$evening[8] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 10) // 10th
						{
							if ($reading->created_at->hour <= 12)
								$morning[9] = $reading->reading_value;
							else
								$evening[9] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 11) // 11th
						{
							if ($reading->created_at->hour <= 12)
								$morning[10] = $reading->reading_value;
							else
								$evening[10] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 12) // 12th
						{
							if ($reading->created_at->hour <= 12)
								$morning[11] = $reading->reading_value;
							else
								$evening[11] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 13) // 13th
						{
							if ($reading->created_at->hour <= 12)
								$morning[12] = $reading->reading_value;
							else
								$evening[12] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 14) // 14th
						{
							if ($reading->created_at->hour <= 12)
								$morning[13] = $reading->reading_value;
							else
								$evening[13] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 15) // 15th
						{
							if ($reading->created_at->hour <= 12)
								$morning[14] = $reading->reading_value;
							else
								$evening[14] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 16) // 16th
						{
							if ($reading->created_at->hour <= 12)
								$morning[15] = $reading->reading_value;
							else
								$evening[15] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 17) // 17th
						{
							if ($reading->created_at->hour <= 12)
								$morning[16] = $reading->reading_value;
							else
								$evening[16] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 18) // 18th
						{
							if ($reading->created_at->hour <= 12)
								$morning[17] = $reading->reading_value;
							else
								$evening[17] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 19) // 19th
						{
							if ($reading->created_at->hour <= 12)
								$morning[18] = $reading->reading_value;
							else
								$evening[18] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 20) // 20th
						{
							if ($reading->created_at->hour <= 12)
								$morning[19] = $reading->reading_value;
							else
								$evening[19] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 21) // 21th
						{
							if ($reading->created_at->hour <= 12)
								$morning[20] = $reading->reading_value;
							else
								$evening[20] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 22) // 22th
						{
							if ($reading->created_at->hour <= 12)
								$morning[21] = $reading->reading_value;
							else
								$evening[21] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 23) // 23th
						{
							if ($reading->created_at->hour <= 12)
								$morning[22] = $reading->reading_value;
							else
								$evening[22] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 24) // 24th
						{
							if ($reading->created_at->hour <= 12)
								$morning[23] = $reading->reading_value;
							else
								$evening[23] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 25) // 25th
						{
							if ($reading->created_at->hour <= 12)
								$morning[24] = $reading->reading_value;
							else
								$evening[24] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 26) // 26th
						{
							if ($reading->created_at->hour <= 12)
								$morning[25] = $reading->reading_value;
							else
								$evening[25] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 27) // 27th
						{
							if ($reading->created_at->hour <= 12)
								$morning[26] = $reading->reading_value;
							else
								$evening[26] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 28) // 28th
						{
							if ($reading->created_at->hour <= 12)
								$morning[27] = $reading->reading_value;
							else
								$evening[27] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 29) // 29th
						{
							if ($reading->created_at->hour <= 12)
								$morning[28] = $reading->reading_value;
							else
								$evening[28] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 30) // 30th
						{
							if ($reading->created_at->hour <= 12)
								$morning[29] = $reading->reading_value;
							else
								$evening[29] = $reading->reading_value;
						}
						else if ($reading->created_at->day == 31) // 31th
						{
							if ($reading->created_at->hour <= 12)
								$morning[30] = $reading->reading_value;
							else
								$evening[30] = $reading->reading_value;
						}
					}

					// set chart's data
					$chart_data = "data: [".implode(",", $morning)."]";
					$chart_data2 = "data: [".implode(",", $evening)."]";
				}
			}
		}
		else
		{
			// get alerts
			$current_alerts = Alert::where('reading_id', Input::get('sensor'))
							->where('created_at','>=', $start_date)
							->where('created_at','<=', $end_date)
							->get();

			// check alert data
			if (count($current_alerts) == 0)
				$display = FALSE;
			else
				$display = TRUE;

			// check statistics particular (average)
			if ($current_particular == "Occurrence")
			{
				// initialize array & variables
				$count = array();
				$chart_data = "";

				for ($i = 0; $i < $label_count; $i++)
					$count[] = 0;

				// set chart's data
				if ($label_count == 12) // annual statistics
				{
					foreach ($current_alerts as $alert)
					{
						if ($alert->created_at->month == 1) // january
							$count[0]++;
						else if ($alert->created_at->month == 2) // february
							$count[1]++;
						else if ($alert->created_at->month == 3) // march
							$count[2]++;
						else if ($alert->created_at->month == 4) // april
							$count[3]++;
						else if ($alert->created_at->month == 5) // may
							$count[4]++;
						else if ($alert->created_at->month == 6) // june
							$count[5]++;
						else if ($alert->created_at->month == 7) // july
							$count[6]++;
						else if ($alert->created_at->month == 8) // august
							$count[7]++;
						else if ($alert->created_at->month == 9) // september
							$count[8]++;
						else if ($alert->created_at->month == 10) // october
							$count[9]++;
						else if ($alert->created_at->month == 11) // november
							$count[10]++;
						else if ($alert->created_at->month == 12) // december
							$count[11]++;
					}
					
					$chart_data = "data: [".implode(",", $count)."]";
				}
				else // monthly statistics with 28, 29, 30, 31 days
				{
					foreach ($current_alerts as $alert)
					{
						if ($alert->created_at->day == 1) // 1st
							$count[0]++;
						else if ($alert->created_at->day == 2) // 2nd
							$count[1]++;
						else if ($alert->created_at->day == 3) // 3rd
							$count[2]++;
						else if ($alert->created_at->day == 4) // 4th
							$count[3]++;
						else if ($alert->created_at->day == 5) // 5th
							$count[4]++;
						else if ($alert->created_at->day == 6) // 6th
							$count[5]++;
						else if ($alert->created_at->day == 7) // 7th
							$count[6]++;
						else if ($alert->created_at->day == 8) // 8th
							$count[7]++;
						else if ($alert->created_at->day == 9) // 9th
							$count[8]++;
						else if ($alert->created_at->day == 10) // 10th
							$count[9]++;
						else if ($alert->created_at->day == 11) // 11th
							$count[10]++;
						else if ($alert->created_at->day == 12) // 12th
							$count[11]++;
						else if ($alert->created_at->day == 13) // 13th
							$count[12]++;
						else if ($alert->created_at->day == 14) // 14th
							$count[13]++;
						else if ($alert->created_at->day == 15) // 15th
							$count[14]++;
						else if ($alert->created_at->day == 16) // 16th
							$count[15]++;
						else if ($alert->created_at->day == 17) // 17th
							$count[16]++;
						else if ($alert->created_at->day == 18) // 18th
							$count[17]++;
						else if ($alert->created_at->day == 19) // 19th
							$count[18]++;
						else if ($alert->created_at->day == 20) // 20th
							$count[19]++;
						else if ($alert->created_at->day == 21) // 21th
							$count[20]++;
						else if ($alert->created_at->day == 22) // 22th
							$count[21]++;
						else if ($alert->created_at->day == 23) // 23th
							$count[22]++;
						else if ($alert->created_at->day == 24) // 24th
							$count[23]++;
						else if ($alert->created_at->day == 25) // 25th
							$count[24]++;
						else if ($alert->created_at->day == 26) // 26th
							$count[25]++;
						else if ($alert->created_at->day == 27) // 27th
							$count[26]++;
						else if ($alert->created_at->day == 28) // 28th
							$count[27]++;
						else if ($alert->created_at->day == 29) // 29th
							$count[28]++;
						else if ($alert->created_at->day == 30) // 30th
							$count[29]++;
						else if ($alert->created_at->day == 31) // 31th
							$count[30]++;
					}

					// set chart's data
					$chart_data = "data: [".implode(",", $count)."]";
				}
			}
		}
		
		// set chart's labels
		if ($label_count == 12)
		{
			$chart_labels = "labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],";
		}
		else if ($label_count == 28)
		{
			$chart_labels = "labels: ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th', '16th',
							'17th', '18th', '19th', '20th', '21st', '22nd', '23rd', '24th', '25th', '26th', '27th', '28th'],";
		}
		else if ($label_count == 29)
		{
			$chart_labels = "labels: ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th', '16th',
							'17th', '18th', '19th', '20th', '21st', '22nd', '23rd', '24th', '25th', '26th', '27th', '28th', '29th'],";
		}
		else if ($label_count == 30)
		{
			$chart_labels = "labels: ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th', '16th',
							'17th', '18th', '19th', '20th', '21st', '22nd', '23rd', '24th', '25th', '26th', '27th', '28th', '29th', '30th'],";
		}
		else if ($label_count == 31)
		{
			$chart_labels = "labels: ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th', '13th', '14th', '15th', '16th',
							'17th', '18th', '19th', '20th', '21st', '22nd', '23rd', '24th', '25th', '26th', '27th', '28th', '29th', '30th', '31st'],";
		}

		return view('statistics', compact('zones', 'parameters', 'readings', 'current_zone', 'current_para', 'month', 'year', 'current_type', 'chart_labels', 'chart_data', 'chart_data2', 'display', 'threshold_type', 'threshold_unit'));
	}

	public function excel() {
		
	}
}
