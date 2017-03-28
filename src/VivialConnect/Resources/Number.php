<?php

namespace VivialConnect\Resources;

use VivialConnect\Common\ResponseException;


class Number extends Resource
{
    public function __construct($data = null)
    {
        parent::__construct($data);

        $this->_singular = 'phone_number';
    }

    public function buy(array $queryParams = [], array $headers = [])
    {
        return $this->save($queryParams, $headers);
    }

    public function buyLocal(array $queryParams = [], array $headers = [])
    {
        $this->phone_number_type = 'local';
        return $this->save($queryParams, $headers);
    }

   /**
    * Lists available phone numbers.
    * 
    * @param string $countryCode - defaults to 'US'.
    * @param string $phoneNumberType - defaults to 'local'.
    * @param array $queryParams - must contain exactly one of the following three keys: in_region, area_code, in_postal_code.
    * @param array $headers - additional headers.
    *
    * @return Collection - a list of available US local (non-toll-free) phone numbers
    */
    public static function searchAvailable($countryCode = 'US', $phoneNumberType = 'local', array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();
        $instance = new $className;
        $accountId = Resource::getCredentialToken(Resource::API_ACCOUNT_ID);
        $resourceUri = "accounts/{$accountId}/numbers/available/{$countryCode}/{$phoneNumberType}.json";
        $response = $instance->getConnection()->get(
            $resourceUri,
            $queryParams, $headers);
        if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            return new Collection($className, $data, $response);
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }

    public static function release($numberId, array $queryParams = [], array $headers = [])
    {
        return Number::delete($numberId, $queryParams, $headers);
    }
}
