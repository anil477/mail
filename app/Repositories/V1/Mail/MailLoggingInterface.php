<?php

namespace App\Repositories\V1\Mail;

interface MailLoggingInterface
{
    /**
     * Log the response from Mail Sending Service
     *
     * @param  string  $uId (can be uuid)
     * @param  orderId $orderId (can be uuid)
     * @param  array   $data
     */
    public function log($uId, $orderId, $response);
}
