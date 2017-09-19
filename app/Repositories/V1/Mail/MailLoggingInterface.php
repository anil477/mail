<?php

namespace App\Repositories\V1\Mail;

interface MailLoggingInterface
{
    /**
     * Log the response from Mail Service
     *
     * @access public
     * @param  array $details
     * @param  array   $data
     */
    public function log($details, $response);
}
