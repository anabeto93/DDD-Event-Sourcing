<?php

namespace Support\SMS;

interface SMSContract
{
    /**
     * @param string $phone_number
     * @param string $message
     */
    public function send($phone_number, $message);

    /**
     * @param string $phone_number
     * @param string $message
     */
    public function flash($phone_number, $message);
}