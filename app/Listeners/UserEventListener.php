<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Log;

class UserEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  auth.login  $event
     * @return void
     */
    public function onUserLogin($event)
    {
        $userId = $event->id;
        $userName = $event->name;
        $logMessage = $userName . " Logged In ";
        
        Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
    }

    public function onUserLogout($event)
    {
        $userId = $event->id;
        $userName = $event->name;
        $logMessage = $userName . " Logged Out ";

        Log::create(['user_id' => $userId, 'log_description' => $logMessage]);
    }
}
