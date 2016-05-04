<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'mobile', 'role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    public static function boot() {
        parent::boot();

        static::saved(function ($user) {
            $userId = Auth::user()->id;
            $username = $user->name;
            $logMessage = "Created a new user '" . $username . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::updated(function ($user) {
            $userId = Auth::user()->id;
            $username = $user->name;
            $logMessage = "Updated details for '" . $username . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });

        static::deleted(function ($user) {
            $userId = Auth::user()->id;
            $username = $user->name;
            $logMessage = "Disabled user '" . $username . "'";

            Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
        });
    }

    
    // mutators
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    public function isActive() {
        return is_null( $this->deleted_at );
    }

    public function isAdmin() {
        return $this->role->role_description == "Administrator";
    }

    // relationships
    public function readings() {
        return $this->hasMany('App\Reading');
    }

    public function logs() {
        return $this->hasMany('App\Log');
    }

    public function alerts() {
        return $this->hasMany('App\Alert');
    }

    public function role() {
        return $this->belongsTo('App\Role');
    }
}

