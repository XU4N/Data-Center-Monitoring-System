<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Log;
use Auth;

class Zone extends Model
{
	public static function boot() {
		parent::boot();

		static::saved(function ($zone) {
			$userId = Auth::user()->id;
			$logMessage = "Added " . $zone->zone_name . " to zones";

			Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
		});

		static::deleted(function ($zone) {
			$userId = Auth::user()->id;
			$logMessage = "Deleted " . $zone->zone_name . " from zones";

			Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
		});
	}

    public function parameter() {
    	return $this->hasMany('App\Parameter');
    }

    public function hasParameters() {
        return ($this->parameter->count() >= 1);
    }


    protected $fillable = ['zone_name'];

    public function getNameAttribute($value) {
    	return ucwords($value);
    }

    // Purpose: return the parameter (id) that is 'temperature' type
    public function getTemperatureTypeParameter() 
    {
        $result = new \Illuminate\Database\Eloquent\Collection;

        if ($this->hasParameters())
        {
            foreach ($this->parameter as $param)
            {
                if ($param->getThresholdCategory() == "Temperature")
                {
                    $result->add($param->id);
                }
            }
        }

        return $result;
    }
    
    // Purpose: return the parameter (id) that is 'humidity' type
    public function getHumidityTypeParameter() 
    {
        $result = new \Illuminate\Database\Eloquent\Collection;

        if ($this->hasParameters())
        {
            foreach ($this->parameter as $param)
            {
                if ($param->getThresholdCategory() == "Humidity")
                {
                    $result->add($param->id);
                }
            }
        }

        return $result;
    }
}
