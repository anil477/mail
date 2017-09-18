<?php

namespace App\Models\V1\Mail;

use Psr\Log\LoggerInterface;
use App\Repositories\V1\Mail\MailingInterface;

class OrderPlacedMail
{
    public function __construct(LoggerInterface $log, MailingInterface $sendEmail)
    {
        $this->logger     = $log;
        $this->mailClient = $sendEmail;
    }

    /**
     * @param $details collection
     */
    public function sendMail($details)
    {
        $template    = 'no-invoice-email-template-id-in-email-client';
        $attachment  = [];
        $invoicePath = data_get($details, 'invoice_path', null);
        if (!is_null($invoicePath)) {
            $attachment['template']  = 'invoice-generated-email-template-id-in-email-client';
            $attachment['fileName']  = 'invoice';
            $attachment['fileType']  =  mime_content_type($details['invoice_path']);
            $attachment['path']      =  $invoicePath;
        }
        return $this->mailClient->send($template, $details, $attachment);
    }
}
