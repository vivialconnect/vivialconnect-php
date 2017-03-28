<?php

namespace VivialConnect\Resources;

use VivialConnect\Common\ResponseException;


class Account extends Resource
{
    /**
     * Get the full resource URI
     *
     * @return string
     */
    public function getResourceUri()
    {
        $uri = "";
        if (($dependencies = $this->getDependencies())) {
           $uri .= "{$dependencies}/";
        }
        $uri .= $this->getResourceName();
        if (($id = $this->getId())) {
            $uri .= "/{$id}";
        }
        $uri .= ".json";
        return $uri;
    }

	public static function billingStatus($accountId = null, array $queryParams = [], array $headers = [])
	{
		$className = get_called_class();
        $instance = new $className;
		$response = $instance->getConnection()->get("/accounts/{$accountId}/status.json", $queryParams, $headers);
		if ($response->isSuccessful()) {
            $instance->response = $response;
            $data = $instance->parseFind($response->getPayload());
            $instance->hydrate($data);
            return $instance;
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
	}

	public static function subAccounts(array $queryParams = [], array $headers = [])
	{
		$className = get_called_class();
        $instance = new $className;
        $accountId = Resource::getCredentialToken(Resource::API_ACCOUNT_ID);
		$response = $instance->getConnection()->get("/accounts/{$accountId}/subaccounts.json", $queryParams, $headers);
		if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            if (property_exists($data, 'accounts') && is_array($data->accounts))
            	return new Collection($className, $data->accounts, $response);
            return $data;
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
	}
}
