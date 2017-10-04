<?php

namespace VivialConnect\Resources;

use VivialConnect\Common\ResponseException;


class Connector extends Resource
{

    public function addNumber($phone_number, $phone_number_id)
    {
        $connector_number = new ConnectorNumber;
        $connector_number->phone_number = $phone_number;
        $connector_number->phone_number_id = $phone_number_id;

        if ($this->id == null){
            $this->save();
        }

        $connector_number->connector_id = $this->id;

        if (count($this->phone_numbers) > 0) {
            $connector_number->save([], [], True);
            $updated_array = Connector::find($this->id)->phone_numbers;
            $this->phone_numbers = $updated_array;
        } else {
            $connector_number->save();
            $updated_array = Connector::find($this->id)->phone_numbers;
            $this->phone_numbers = $updated_array;
        }
    }

    public function addNumbers(array $numbers = [])
    {
        foreach($numbers as $number) {
            $this->addNumber($number[0], $number[1]);
        }
    }

    public function deleteNumber($phone_number, $phone_number_id)
    {
        $connector_number = new ConnectorNumber;
        $connector_number->phone_number = $phone_number;
        $connector_number->phone_number_id = $phone_number_id;
        $connector_number->connector_id = $this->id;
        $connector_number->destroy();
    }

    public function deleteNumbers(array $numbers = [])
    {
        foreach($numbers as $number) {
            $this->deleteNumber($number[0], $number[1]);
        }
    }

    public function addCallback($event_type, $message_type, $url, $method)
    {
        $connnector_callback = new ConnectorCallback;
        $connnector_callback->event_type = $event_type;
        $connnector_callback->message_type = $message_type;
        $connnector_callback->url = $url;
        $connnector_callback->method = $method;

        if ($this->id == null){
            $this->save();
        }

        $connnector_callback->connector_id = $this->id;

        if (count($this->callbacks) > 0) {
            $connnector_callback->save([], [], True);
            $updated_array = Connector::find($this->id)->callbacks;
            $this->callbacks = $updated_array;
        } else {
            $connnector_callback->save();
            $updated_array = Connector::find($this->id)->callbacks;
            $this->callbacks = $updated_array;
        }
    }

    public function addCallbacks(array $callbacks = [])
    {
        foreach($callbacks as $callback) {
            $this->addCallback($callback[0], $callback[1], $callback[2], $callback[3]);
        }
    }

    public function deleteCallback($event_type, $message_type)
    {
        $connnector_callback = new ConnectorCallback;
        $connnector_callback->event_type = $event_type;
        $connnector_callback->message_type = $message_type;
        $connnector_callback->connector_id = $this->id;
        $connnector_callback->destroy();
    }

    public function deleteCallbacks(array $callbacks = [])
    {
        foreach($callbacks as $callback) {
            $this->deleteCallback($callback[0], $callback[1]);
        }
    }

}

class ConnectorNumber extends Resource
{

    /**
     * Get the full resource URI
     *
     * @return string
     */
    public function getResourceUri()
    {
        $uri = sprintf(self::API_ACCOUNT_PREFIX,
            self::getCredentialToken(self::API_ACCOUNT_ID));
        $uri .= "connectors/";
        $connector_id = $this->connector_id;
        $uri .= "{$connector_id}/phone_numbers.json";
        return $uri;
    }

    protected function processConnectorNumber(array $attributes = [])
    {
        /* pop the connector id */
        array_pop($attributes);
        return ["connector" => ["phone_numbers" => [$attributes]]];
    }

}

class ConnectorCallback extends Resource
{

    /**
     * Get the full resource URI
     *
     * @return string
     */
    public function getResourceUri()
    {
        $uri = sprintf(self::API_ACCOUNT_PREFIX,
            self::getCredentialToken(self::API_ACCOUNT_ID));
        $uri .= "connectors/";
        $connector_id = $this->connector_id;
        $uri .= "{$connector_id}/callbacks.json";
        return $uri;
    }

    protected function processConnectorCallback(array $attributes = [])
    {
        /* pop the connector id */
        array_pop($attributes);
        return ["connector" => ["callbacks" => [$attributes]]];
   }

}