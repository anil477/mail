<?php

namespace App\Models\V1\Mail;

use Psr\Log\LoggerInterface;
use App\Models\V1\User\UserRepo;
use App\Models\V1\Orders\OrderRepo;
use App\Exceptions\InvalidUserException;
use App\Exceptions\InvalidOrderException;

class MailingDetails
{
    public function __construct(LoggerInterface $log, OrderRepo $order, UserRepo $user)
    {
        $this->logger = $log;
        $this->user   = $user;
        $this->order  = $order;
    }

    /**
     * @param string $uID
     * @param string $orderId
     * @return collection
     * @throws InvalidUserException
     * @throws InvalidOrderException
     */
    public function fetchDetails($uID, $orderId)
    {
        // fetch the order details
        $orderDetails = $this->order->fetchOrderDetails($orderId);
        if ($orderDetails->isEmpty()) {
            throw new InvalidOrderException;
        }

        // fetch user details
        $userDetails = $this->user->fetchUserDetails($uID);
        if ($userDetails->isEmpty()) {
            throw new InvalidUserException;
        }

        return $userDetails->merge($orderDetails);
    }
}
