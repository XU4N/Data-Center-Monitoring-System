<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Parameter;
use App\Zone;
use Carbon\Carbon;
use DB;

class Reading extends Model
{
	//The first day of this month and previous month
    private $startOfThisMonth;
    private $startOfPreviousMonth;

	//The last day of this month and previous month
    private $lastDayOfThisMonth;
    private $lastDayOfPreviousMonth;	

    public function __construct() {
		//Initialise this month with today's date
	    $this->startOfThisMonth = Carbon::now()->startOfMonth();

		//Initialise the last date of this month
	    $this->lastDayOfThisMonth = Carbon::now()->endOfMonth();

	    //Initiliase and get the previous month and initialise the day to first of that month;
	    $this->startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();

	    //Initialise and get the last day of the previous month
	    $this->lastDayOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
    }

    public static function boot() {
        parent::boot();
		

        static::saving(function ($reading){
        	
        });

        static::saved(function ($reading) {
            $userId = Auth::user()->id;
            $readingValue = $reading->reading_value;
            $parameterName = Parameter::find($reading->parameter_id)->parameter_name;
            $zoneName = Zone::find($reading->parameter->zone->id)->zone_name;
            $logMessage = "Recorded a reading of '" . $reading->reading_value . "' for " . $parameterName . " in " . $zoneName;

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::deleted(function ($reading) {
   			$userId = Auth::user()->id;
            $readingValue = $reading->reading_value;
            $parameterName = Parameter::find($reading->parameter_id)->parameter_name;
            $zoneName = Zone::find($reading->parameter->zone->id)->zone_name;
            $logMessage = "Deleted reading with value '" . $reading->reading_value . "' from  " . $parameterName . " in " . $zoneName;

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);

        });
    }

	protected $fillable = ['parameter_id', 'reading_value', 'user_id'];

	protected $appends = ['threshold_category', 'zone_id'];

	public function user() {
		return $this->belongsTo('App\User')->withTrashed();
	}

	public function parameter() {
		return $this->belongsTo('App\Parameter');
	}

	public function getReadingValueAttribute($value) {
		return $value;
	}

	public function getThresholdIdAttribute() {
		return $this->attributes['threshold_id'] = $this->parameter->threshold->id;
	}

	public function getThresholdCategoryAttribute() {
		return $this->attributes['threshold_category'] = $this->parameter->threshold->threshold_category;
	}

	public function getZoneIdAttribute() {
		return $this->attributes['zone_id'] = $this->parameter->zone->id;
	}

	public function isBoolean() {
		return $this->parameter->parameter_type == "boolean";
	}

	public function getBooleanStatus() {
		if ($this->reading_value == 1)
			return "Normal";

		return "Faulty";
	}

	public function getFirstOfPreviousMonth() {
		return $this->startOfPreviousMonth;
	}

	public function readingStatus() {
		return $this->parameter->threshold->getStatus($this->reading_value);
	}
	
	public function scopeSort($query)
	{
		return $query->orderBy('created_at', 'desc');
	}

	public function scopeOfParameter($query, $parameter) {
		return $query->where('parameter_id', $parameter);
	}

	public function scopeOfThreshold($query, $threshold) {
		return $query->where('threshold_category', $threshold);
	}

	public function scopeOfReadings($query) {
        return $query->where('created_at', '>=', Carbon::now()->subDay());
    }

    public function scopeThisMonth($query) {
    	return $query->whereBetween('readings.created_at', [$this->startOfThisMonth, $this->lastDayOfThisMonth]);
    }

    public function scopePreviousMonth($query) {
    	return $query->whereBetween('readings.created_at', [$this->startOfPreviousMonth, $this->lastDayOfPreviousMonth]);
    }

    public function scopeReadingsForMonth($query, $months) {
    	$customMonthStart = Carbon::now()->subMonths($months)->startOfMonth();
    	$customMonthEnd = Carbon::now()->subMonths($months)->endOfMonth();

    	return $query->whereBetween('readings.created_at', [$customMonthStart, $customMonthEnd]);
    }

    public function getReadingValue() {
		return $this->reading_value;
	}

	public function isInThisZone($zoneID) {
		$this->attributes['zone_id'] = Parameter::find($this->parameter_id)->zone->id;
		return $this->attributes['zone_id'] == $zoneID;
	}
}
