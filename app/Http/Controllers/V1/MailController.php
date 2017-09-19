<?php

namespace App\Http\Controllers\V1;

use App\Helpers\JsendResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MailRequest;
use App\Models\V1\Mail\MailingDetails;
use App\Models\V1\Mail\OrderPlacedMail;

//

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        MailingDetails $mailingDetails,
        OrderPlacedMail $sendOrderMail
    ) {
        $this->sendOrderMail      = $sendOrderMail;
        $this->mailingDetails     = $mailingDetails;
    }

    public function sendOrderMail(MailRequest $request)
    {
        $data       = $request->all();
        $details    = $this->mailingDetails->fetchDetails($data['u_id'], $data['order_id']);
        $response   = $this->sendOrderMail->queueMail($details);

        /*
         * @todo: Use Transformer for response: https://github.com/spatie/laravel-fractal
         */
        return JsendResponse::make($response, 'Mail Queued Successfully');
    }
}
