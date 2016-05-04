<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Parameter extends Model
{
    protected $fillable = [
    	'parameter_name', 
    	'parameter_description', 
    	'parameter_type', 
    	'zone_id', 
    	'threshold_id',
        'created_at'
	];
        
    public static function boot() {
        parent::boot();

        static::updated(function ($param) {
            $userId = Auth::user()->id;
            $logMessage = "Updated parameter '" . $param->parameter_name . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::saved(function ($param) {
            $userId = Auth::user()->id;
            $logMessage = "Added a new parameter '" . $param->parameter_name . "' to parameters";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::deleted(function ($param) {
            $userId = Auth::user()->id;
            $logMessage = "Removed parameter '" . $param->parameter_name . "' from parameters";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });
    }

    public function reading() {
        return $this->hasMany('App\Reading');
    }

    public function threshold() {
        return $this->belongsTo('App\Threshold');
    }

    public function zone() {
        return $this->belongsTo('App\Zone');
    }

    public function scopeOfZone($query, $zone) {
        return $query->where('zone_id', $zone);
    }

    public function scopeOfParameterName($query, $name) {
        return $query->where('parameter_name', $name);
    }

    public function hasReadings() {
        return ($this->reading->count() >= 1);
    }

    public function getThresholdCategory() { 
        return $this->threshold->getThresholdCategory();
    }

    public function getThresholdUnit() { 
        return $this->threshold->getThresholdUnit();
    }
}
