<?php

namespace App\Models\V1\Mail;

use Log;
use SendGrid;
use Exception;
use SendGrid\Mail;
use SendGrid\Email;
use Sendgrid\Attachment;
use SendGrid\Personalization;
use App\Repositories\V1\Mail\MailingInterface;

class SendGridHelper implements MailingInterface
{
    public function __construct($sendGridKey)
    {
        $this->sendGridKey = $sendGridKey;
    }

    /**
     * @access public
     * @param   string $template
     * @param   array  $details
     * @param   array  $attachDetails
     */
    public function send($template, $details, $attachDetails)
    {
        $personalize = new Personalization();
        $to          = new Email(null, ['email']);
        $personalize->addTo($to);

        $personalize->addSubstitution('--sender-info--', 'Your Local Merchant!!!');

        if (!empty($details)) {
            foreach ($details as $subkey => $subvalue) {
                $personalize->addSubstitution($subkey, (string)$subvalue);
            }
        }

        $mail = new Mail();
        $from = new Email('Test', 'support@merchant.com');
        $mail->setFrom($from);
        $mail->setTemplateId($template);
        $mail->addPersonalization($personalize);

        if (!empty($attachDetails)) {
            $attachment = $this->setupAttachment($attachDetails);
            $mail->addAttachment($attachment);
        }

        try {
            $sg       = new SendGrid($this->sendGridKey);
            $response = $sg->client->mail()->send()->post($mail);

            /*
             * @todo: Better Error Handling
             */
            return $response;
        } catch (Exception $e) {
            Log::error('mail.send.error.sendgrid', ['message' => $e->getMessage()]);
        }
    }

    /**
     * @access private
     * @param   array  $attachDetails
     */
    private function setupAttachment($attachDetails)
    {
        $attachment = new Attachment();
        $attachment->setContent($attachDetails['path']);
        $attachment->setType($attachDetails['fileType']);
        $attachment->setFilename($attachDetails['fileName']);
        $attachment->setDisposition("attachment");

        return $attachment;
    }
}
