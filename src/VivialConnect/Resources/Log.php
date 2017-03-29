<?php

namespace VivialConnect\Resources;


class Log extends Resource
{
    /**
     * List all log resources
     *
     * @return Collection|boolean
     */
    public static function all(array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();
        /** @var self $instance */
        $instance = new $className;
        $response = $instance->getConnection()->get($instance->getResourceUri(), $queryParams, $headers);
        if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            return new Collection($className, $data->log_items, $response, $data->last_key);
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }

    /**
     * List aggregate log resources
     *
     * @return Collection|boolean
     */
    public static function aggregate(array $queryParams = [], array $headers = [])
    {
        $className = get_called_class();
        /** @var self $instance */
        $instance = new $className;
        $accountId = Resource::getCredentialToken(Resource::API_ACCOUNT_ID);
        $resourceUri = "accounts/{$accountId}/logs/aggregate.json";
        $response = $instance->getConnection()->get($resourceUri, $queryParams, $headers);
        if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            return new Collection($className, $data->log_items, $response, $data->last_key);
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }
}
