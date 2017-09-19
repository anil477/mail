<?php

namespace App\Models\V1\Mail;

use Psr\Log\LoggerInterface;
use App\Events\V1\SendOrderMailEvent;
use Illuminate\Contracts\Events\Dispatcher;
use App\Repositories\V1\Mail\MailingInterface;

class OrderPlacedMail
{
    public function __construct(LoggerInterface $log, Dispatcher $dispatch)
    {
        $this->logger = $log;
        $this->dispatcher  = $dispatch;
    }

    /**
     * @param $details collection
     */
    public function queueMail($details)
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

        // Dispatch the task
        $this->dispatcher->dispatch(new SendOrderMailEvent($template, $details, $attachment));
        $this->logger->info('order.placed.mail.queued', [
                'template'   => $template,
                'details'    => $details,
                'attachment' => $attachment,
                'order_id'   => $details['order_id']
            ]);

        return true;
    }
}
