<?php

namespace App\Listeners;

use App\Events\AbnormalReadingWasTaken;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAlertSMS
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
        $readingValue = $event->reading->reading_value;
        $status = $event->reading->readingStatus();
        $zone = $event->reading->parameter->zone->zone_name;
        $parameter = $event->reading->parameter->parameter_name;
        $text = "\"" . $status . " reading for " . $parameter . " in " . $zone . ". Reading: " . $readingValue . ".\"";
        $strText = (string)$text;

        // $users = User::all();
        // foreach($users as $user)
        // {
        //     $url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
        //     'api_key' => '02cb9bd7',
        //     'api_secret' => 'fc903a3c',
        //     'to' => $user->mobile,
        //     'from' => '60162817981',
        //     'text' => $strText
        //     ]);

        //     $ch = curl_init($url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     $response = curl_exec($ch);
        // }

        // $url = 'https://rest.nexmo.com/sms/json?' . http_build_query([
        // 'api_key' => '02cb9bd7',
        // 'api_secret' => 'fc903a3c',
        // 'to' => '601116238619',
        // 'from' => '60162817981',
        // 'text' => $strText
        // ]);

        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
    }
}


