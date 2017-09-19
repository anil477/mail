<?php

namespace App\Listeners\V1;

use Exception;
use Psr\Log\LoggerInterface;
use App\Events\V1\SendOrderMailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\V1\Mail\MailingInterface;
use App\Repositories\V1\Mail\MailLoggingInterface;

class SendOrderMailListener implements ShouldQueue
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MailingInterface
     */
    private $mailClient;

    /**
     * @var MailLoggingInterface
     */
    private $loggingMailDetails;

    public function __construct(LoggerInterface $log, MailingInterface $sendEmail, MailLoggingInterface $loggingMailDetails)
    {
        $this->logger     = $log;
        $this->mailClient = $sendEmail;
        $this->loggingMailDetails = $loggingMailDetails;
    }

    public function handle(SendOrderMailEvent $event)
    {
        try {
            $response = $this->mailClient->send($event->getTemplate(), $event->getDetails(), $event->getAttachment());
            $this->loggingMailDetails->log($event->getDetails(), $response);
        } catch (Exception $e) {
            $this->logger->error('mail.service.exception', ['error_message' => $e->getMessage()]);
        }
    }
}
