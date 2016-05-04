<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Parameter;
use App\Log;

class Alert extends Model
{
	public static function boot() {
		parent::boot();

		static::updated(function ($alert) {
			$userId = Auth::user()->id;
			$alertActionTaken = $alert->action_taken;
			$parameter = Parameter::find($alert->reading_id)->parameter_name;
			$logMessage = "Updated alert status to '" . $alertActionTaken . "' for " . $parameter;

			Log::create(['user_id' => $userId, 'log_description' => $logMessage]);

		});
	}

 	protected $fillable = [
 		'alert_description', 
 		'user_id', 
 		'action_taken',
 		'reading_id'
	];

	protected $appends = [
		'zone_id'
	];

	public function getAlertDescriptionAttribute($value) {
		return $value;
	}

	public function scopeOfParameter($query, $id) {
		return $query->where('reading_id', $id)->orderBy('created_at', 'desc');
	}

	public function requiresAttention() {
		return $this->action_taken == "Attention Needed";
	}

	public function isInProgress() {
		return $this->action_taken == "In Progress";
	}

	public function isResolved() {
		return $this->action_taken == "Resolved";
	}

	public function getZoneIdAttribute() {
		return $this->attributes['zone_id'] = Parameter::find($this->reading_id)->zone->id;
	}

	public function isInThisZone($zoneID) {
		$this->attributes['zone_id'] = Parameter::find($this->reading_id)->zone->id;
		return $this->attributes['zone_id'] == $zoneID;
	}
}
