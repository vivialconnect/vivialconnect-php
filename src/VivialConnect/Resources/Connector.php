<?php

namespace VivialConnect\Resources;

use VivialConnect\Common\ResponseException;


class Connector extends Resource
{

    public function addNumber($phoneNumber, $phoneNumberId)
    {
        $connectorNumber = new ConnectorNumber;
        $connectorNumber->phone_number = $phoneNumber;
        $connectorNumber->phone_number_id = $phoneNumberId;

        if ($this->id == null){
            $this->save();
        }

        $connectorNumber->connector_id = $this->id;

        if (count($this->phone_numbers) > 0) {
            $connectorNumber->save([], [], True);
            $updatedArray = Connector::find($this->id)->phone_numbers;
            $this->phone_numbers = $updatedArray;
        } else {
            $connectorNumber->save();
            $updatedArray = Connector::find($this->id)->phone_numbers;
            $this->phone_numbers = $updatedArray;
        }
    }

    public function addNumbers(array $numbers = [])
    {
        foreach($numbers as $number) {
            $this->addNumber($number[0], $number[1]);
        }
    }

    public function deleteNumber($phoneNumber, $phoneNumberId)
    {
        $connectorNumber = new ConnectorNumber;
        $connectorNumber->phone_number = $phoneNumber;
        $connectorNumber->phone_number_id = $phoneNumberId;
        $connectorNumber->connector_id = $this->id;
        $connectorNumber->destroy([], [], True);
    }

    public function deleteNumbers(array $numbers = [])
    {
        foreach($numbers as $number) {
            $this->deleteNumber($number[0], $number[1]);
        }
    }

    public function addCallback($eventType, $messageType, $url, $method)
    {
        $connectorCallback = new ConnectorCallback;
        $connectorCallback->event_type = $eventType;
        $connectorCallback->message_type = $messageType;
        $connectorCallback->url = $url;
        $connectorCallback->method = $method;

        if ($this->id == null){
            $this->save();
        }

        $connectorCallback->connector_id = $this->id;

        if (count($this->callbacks) > 0) {
            $connectorCallback->save([], [], True);
            $updatedArray = Connector::find($this->id)->callbacks;
            $this->callbacks = $updatedArray;
        } else {
            $connectorCallback->save();
            $updatedArray = Connector::find($this->id)->callbacks;
            $this->callbacks = $updatedArray;
        }
    }

    public function addCallbacks(array $callbacks = [])
    {
        foreach($callbacks as $callback) {
            $this->addCallback($callback[0], $callback[1], $callback[2], $callback[3]);
        }
    }

    public function deleteCallback($eventType, $messageType)
    {
        $connectorCallback = new ConnectorCallback;
        $connectorCallback->event_type = $eventType;
        $connectorCallback->message_type = $messageType;
        $connectorCallback->connector_id = $this->id;
        $connectorCallback->destroy([], [], True);
    }

    public function deleteCallbacks(array $callbacks = [])
    {
        foreach($callbacks as $callback) {
            $this->deleteCallback($callback[0], $callback[1]);
        }
    }

}

class ConnectorNumber extends SubResource {}

class ConnectorCallback extends SubResource {}
