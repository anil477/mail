<?php

namespace App\Http\Controllers\V1;

use App\Helpers\JsendResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MailRequest;
use App\Models\V1\Mail\MailingDetails;
use App\Models\V1\Mail\OrderPlacedMail;
use App\Repositories\V1\Mail\MailLoggingInterface;

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        MailingDetails $mailingDetails,
        OrderPlacedMail $sendOrderMail,
        MailLoggingInterface $loggingMailDetails
    ) {
        $this->sendOrderMail      = $sendOrderMail;
        $this->mailingDetails     = $mailingDetails;
        $this->loggingMailDetails = $loggingMailDetails;
    }

    public function sendOrderMail(MailRequest $request)
    {
        $data       = $request->all();
        $details    = $this->mailingDetails->fetchDetails($data['u_id'], $data['order_id']);
        $response   = $this->sendOrderMail->sendMail($details);

        $this->loggingMailDetails->log($data['u_id'], $data['order_id'], $response);

        /*
         * @todo: Use Transformer for response: https://github.com/spatie/laravel-fractal
         */
        if ($response->statusCode() > 203) {
            // as per SendGrid Docs
            return JsendResponse::make($response, 'Mail Sending Failed', 400);
        }

        return JsendResponse::make($response, 'Mail Sent Successfully');
    }
}
