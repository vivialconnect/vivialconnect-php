<?php

namespace VivialConnect\Resources;

/**
* For manipulating Account resources
*/

class Account extends Resource {

    /**
    * Get current account status for free trial
    *
    * $status = Account::billingStatus($accountId);
    *
    * @param integer|string|null $accountId
    * @param array $queryParams 
    * @param array $headers - additional headers.
    *
    * @return bool 
    */
    public static function billingStatus($accountId = null, array $queryParams = [], array $headers = [])
    {}

}