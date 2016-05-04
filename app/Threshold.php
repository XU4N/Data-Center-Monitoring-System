<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Log;
use Auth;

class Threshold extends Model
{

    public static function boot() {
        parent::boot();

        static::saved(function ($threshold) {
            $userId = Auth::user()->id;
            $threshold_name = $threshold->threshold_category;
            $logMessage = "Added a threshold '" . $threshold_name . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::updated(function ($threshold) {
            $userId = Auth::user()->id;
            $threshold_name = $threshold->threshold_category;
            $logMessage = "Updated threshold '" . $threshold_name . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::deleted(function ($threshold) {
            $userId = Auth::user()->id;
            $threshold_name = $threshold->threshold_category;
            $logMessage = "Removed threshold '" . $threshold_name . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });
    }

    public function parameter() {
    	return $this->hasMany('App\Parameter');
    }

    protected $fillable = [
        'threshold_category', 
        'units', 
        'min_critical_value',
        'min_warning_value',
        'normal_value',
        'max_warning_value',
        'max_critical_value'
    ];

    public function readings()
    {
        return $this->hasManyThrough('App\Reading','App\Parameter');
    }

    public function getThresholdCategoryAttribute($value) {
        return ucwords($value);
    }

    public function getMinCriticalValueAttribute($value) {
        return $value;
    }

    public function getMinWarningValueAttribute($value) {
        return $value;
    }

    public function getNormalValueAttribute($value) {
        return $value;
    }

    public function getMaxWarningValueAttribute($value) {
        return $value;
    }

    public function getMaxCriticalValueAttribute($value) {
        return $value;
    }

    public function getStatus($value) {
        $param = $this->parameter->first();
        
        if($param->parameter_type == "boolean") {
            if ($value == 1)
                return "Normal";
            return "Critical";
        }

        if ($value >= $this->max_critical_value || $value <= $this->min_critical_value)
            return "Critical";
        elseif (($value < $this->max_critical_value || $value > $this->min_critical_value) 
             && ($value >= $this->max_warning_value || $value <= $this->min_warning_value))
            return "Warning";
        elseif (($value <= $this->normal_value || $value >= $this->normal_value) 
             && ($value > $this->min_warning_value || $value < $this->max_warning_value))
            return "Normal";
        else
            return "Critical";
    }

    public function getThresholdCategory() { 
        return $this->threshold_category;
    }

    public function getThresholdUnit() { 
        return $this->units;
    }
}
