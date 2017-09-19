<?php

namespace App\Models\V1\User;

use Illuminate\Database\Connection;

class UserRepo
{
    /**
     * DB Table for order details
     */
    const USER_TABLE = 'users';

    public function __construct(Connection $dbManager)
    {
        $this->dbManager = $dbManager;
    }

    /**
     * @param string $uId
     * @return Collection
     */
    public function fetchUserDetails($uID)
    {
        /*
         * These details can be fetched from a separate user microservice or cache or db
         */
        return collect([
            'name'            => 'Karan Dev',
            'billing_address' => 'Banaglore',
            'email'           => 'dev@dev.net',
            'u_id'            => 'uuid-122'
        ]);

        // this will return a collection with the user details
        return $this->dbManager->table(self::USER_TABLE)
                    ->where('user_id', $uID)
                    ->select('billing_address', 'name', 'email', 'u_id')
                    ->first();
    }
}
