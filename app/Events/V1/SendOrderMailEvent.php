<?php

namespace App\Events\V1;

use App\Events\Event;

class SendOrderMailEvent extends Event
{

    private $details;

    private $template;

    private $attachment;

    public function __construct($template, $details, $attachment)
    {
        $this->details    = $details;
        $this->template   = $template;
        $this->attachment = $attachment;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getAttachment()
    {
        return $this->attachment;
    }
}
