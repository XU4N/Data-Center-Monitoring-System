<?php
namespace App;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class IntelligentPredict {
	private $counter;
	private $offset; //the offset temperature which is determined to be high
	private $offsetMaximum; //critical offset temperature to determine if its wasteful or not
	private $thisMonthReadings;
	private static $monitoringPeriod = 3;
	private $previousMonthReadings;
	private $temperatureDifference;
	private $defaultTemperature;

	public function __construct() {
		$this->counter = 0;
		$this->offset = 1.5;
		$this->offsetMaximum = 2.8;
		$this->defaultTemperature = Threshold::find(2)->normal_value;
	}

	public static function getMonitoringPeriod() {
		return self::$monitoringPeriod;
	}

	public function getOffset(){
		return $this->offset;
	}

	public function getMonitoringPeriodInWords() {
		$startDate = Carbon::now()->subMonths(self::getMonitoringPeriod() - 1)->startOfMonth();
		$endDate = Carbon::now()->endOfMonth();

		$strStartDate = $startDate->format('F Y');
		$strEndDate = $endDate->format('F Y');

		return $strStartDate . " - " . $strEndDate;
	}

	public function getMonitoringPeriodLegend(){
		$legend = array();

		for ($i=0; $i < self::getMonitoringPeriod(); $i++) { 
			$formattedDate = Carbon::now()->startOfMonth()->subMonths($i)->format('M \'y');
			array_push($legend, "\"".$formattedDate."\"");
		}

		return array_reverse($legend);
	}

	/*
		Prepares the readings to be used by the intelligent predict algorithm
		input: zone_id
		output: Collection with average reading for each month with respect to the zone_id;
	*/
	public static function prepareReadings($filter_by_zone_id) {
		//check the monitoring period
		$readings = new Collection();
		for ($i=0; $i < self::getMonitoringPeriod(); $i++) { 
			$reading = Reading::readingsForMonth($i)->get()->filter(function ($item) use ($filter_by_zone_id){
				return $item->threshold_id == 2 && $item->zone_id == $filter_by_zone_id;
			})->avg('reading_value');
			if (is_null($reading)) {
				$reading = 0;
			}
			$readings->prepend($reading);
		}

		return $readings;
	}

	public function getDefaultTemperature() {
		return $this->defaultTemperature;
	}

	// public function getAverageReadingForThisMonth() {
	// 	return $this->thisMonthReadings->avg();
	// }

	// public function getAverageReadingForPreviousMonth() {
	// 	return $this->previousMonthReadings->avg();
	// }

	public function getFeedbackMessage($readings) {
		$feedbackMessage = "";
		$delta = $this->calculateDelta($readings);
		//decreasing temperature
			//case 1: Between -2 and -5
			//case 2: Greater than -5
			//case 3: Normal
		//increasing temperature
			//case 1: Between 2 and 5
			//case 2: Greater than 5
			//case 3: Normal
		if ($this->counter == 3) {
			if ($delta <= -$this->offset && $delta >= -$this->offsetMaximum) {
				//decreasing temperature case 1
				return "The temperature is decreasing steadily. Overcooling the data centre may cost you with high energy bills.";

			} else if ($delta < 0 && $delta <= -$this->offsetMaximum) {
				//decreasing temperature case 2
				return "The temperature is decreasing rapidly. Please inspect your air conditioning unit to see if it has malfunctioned and adjust the temperature up a couple of degrees. Overcooling your data centre will raise your energy bills significantly.";

			} else if($delta >= $this->offset && $delta <= $this->offsetMaximum) {
				//increasing temperature case 1
				return "The temperature is rising steadily. Further increase in the temperature may affect the performance of the servers.";

			} else if($delta > $this->offsetMaximum) {
				//increasing temperature case 2
				return "The temperature is rising rapidly. Please check your air conditioning units to see if they have malfunctioned. You may need to clean the filters or service the unit.";

			} else {
				//normal case 3 for both, increasing and decreasing temperature
				return "Everything appears to be normal! The system will continue to monitor the system and warn you of any imminent issues to help you weed out potential problems proactively";
			}
		}
		if ($delta > $this->offset || $delta < -$this->offset) {
			return "Although there is a large degree of temperature variation, it does not suggest that there may be a problem with the sensor/air conditioning system. The system checks for continuous rise in temperature or decrease in temperature to determine potential problems with your air contioning equipment or the sensor module itself.";
		} 
		return "Everything appears to be normal! The system will continue to monitor the system and warn you of any imminent issues to help you weed out potential problems proactively";
	}

	public function calculateDelta($readings) {
		$delta = 0;

		for ($i=1; $i < $readings->count(); $i++) { 
			$delta += $readings[$i] - $readings[$i-1];
		}

		return $delta;
	}

	public function calculateTrend($readings){
		
		$anyReadingsIsDefault = false;

		for ($i=0; $i<=$readings->count()-1; $i++)
		{
			if($readings[$i] == $this->defaultTemperature)
			{
				$this->counter = 0;
				$anyReadingsIsDefault = true;
			}
		}

		if($anyReadingsIsDefault == false) {
			$check = 0;
			if ($readings[0] > $this->defaultTemperature){
				$this->counter++;
				$check = 1;
			}
			if ($readings[0] < $this->defaultTemperature){
				$this->counter++;
				$check = -1;
			} 

			for ($i=1; $i <= $readings->count() - 1; $i++) { 
				if ($readings[$i] > $this->defaultTemperature) {
					if ($check == 1){
						if ($readings[$i] > $readings[$i-1]) {
							$this->counter++;
						} 
						else {
							$this->counter = 0;
						}
					}
					else { // crossover happens
						$this->counter = 0;
						$check = 1;
					}
				} else if ($readings[$i] < $this->defaultTemperature) {
					if ($check == -1){
						if ($readings[$i] < $readings[$i-1]) {
							$this->counter++;
						} 
						else {
							$this->counter = 0;
						}
					}
					else { //cross happens
						$this->counter = 0;
						$check = -1;
					}
				} else {
					$this->counter = 0;
				}
			}
		}
		return $this->counter;
	}

}