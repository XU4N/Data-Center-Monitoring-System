<?php

namespace App\Listeners;

use App\Alert;
use App\Parameter;
use App\Events\AbnormalReadingWasTaken;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class AddReadingToAlertsTable implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AbnormalReadingWasTaken  $event
     * @return void
     */
    public function handle(AbnormalReadingWasTaken $event)
    {
        //note in alerts table the parameter_id is reading_id

        //Populate the Alert Description
        $parameter = Parameter::find($event->reading->parameter_id)->parameter_name;
        $status = $event->reading->readingStatus();
        $reading_value = $event->reading->reading_value;
        $alert_description = $status . " reading for " . $parameter . ": Reading is " . $reading_value;

        $alert = new Alert();
        $alert->alert_description = $alert_description;
        $alert->user_id = $event->reading->user_id;
        $alert->action_taken = "Attention Needed"; //This is the default action taken
        $alert->reading_id = $event->reading->parameter_id;
        $alert->save();
    }
}
