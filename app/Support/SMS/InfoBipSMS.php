<?php

namespace Support\SMS;

use Illuminate\Support\Facades\Log;

class InfoBipSMS implements SMSContract
{
    public function send($phone_number, $message)
    {
        Log::debug('Supposed to send an SMS', ['phone_number' => $phone_number, 'message' => $message]);
    }

    public function flash($phone_number, $message)
    {
        Log::debug('Supposed to send a flash message', ['phone_number' => $phone_number, 'message' => $message]);
    }
}