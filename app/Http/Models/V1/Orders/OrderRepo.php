<?php

namespace App\Models\V1\Orders;

use Illuminate\Database\Connection;

class OrderRepo
{
    /**
     * DB Table for order details
     */
    const ORDERS_TABLE = 'orders';

    public function __construct(Connection $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    /**
     * @param string $orderId
     * @return Collection
     */
    public function fetchOrderDetails($orderId)
    {
        // this will return a collection with the order details
        return collect([
            'delivery_address'  => 'Banaglore',
            'expected_delivery' => '29 September, 2017',
            'product_name'      => 'Iphone X',
            'invoice_path'      => null
        ]);

        /*
         * @todo: Will this read be classified as a critical read ?
         * Will have to be handled accordingly
         */
        return $this->dbManager->table(self::ORDERS_TABLE)
                    ->where('u_id', $uID)
                    ->where('order_id', $orderId)
                    ->select('delivery_address', 'expected_delivery', 'product_name', 'invoice_path')
                    ->first();
    }
}
