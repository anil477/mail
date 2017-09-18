<?php

namespace App\Repositories\V1\Mail;

interface MailingInterface
{
    /**
     * Send Mail
     * @access public
     * @param   string $template
     * @param   array  $details
     * @param   array  $attachDetails
     */
    public function send($template, $details, $attachDetails);
}
