#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\Connector;
use VivialConnect\Resources\Message;

/* Example Code for Creating and Manipulating Connectors */

/* Create a new connector */

$connector = new Connector;
$connector->name = "My First Connector";
$connector->save();

/* Add a number to the connector 
   - first argument is the phone number
   - second argument is the phone number's id
*/

$connector->addNumber("+1XXXXXXXXXX", 151);

/* Remove a number from a connector 
   - first argument is the phone number
   - second argument is the phone number's id
*/

$connector->deleteNumber("+1XXXXXXXXXX", 151);

/* Add multiple numbers to the connector
   - takes an array of arrays i.e [["+1XXXXXXXXXX", 149], ["+1XXXXXXXXXX", 151]]
   - subarray's first argument is the phone number
   - subarray's second argument is the phone number's id
*/

$connector->addNumbers([["+1XXXXXXXXXX", 149], ["+1XXXXXXXXXX", 151]]);


/* Delete multiple numbers from the connector
   - takes an array of arrays i.e [["+1XXXXXXXXXX", 149], ["+1XXXXXXXXXX", 151]]
   - subarray's first argument is the phone number
   - subarray's second argument is the phone number's id
*/

$connector->deleteNumbers([["+1XXXXXXXXXX", 149], ["+1XXXXXXXXXX", 151]]);

 /* Add a callback to a connector
    - first argument is the event type ('incoming', 'incoming_fallback', or 'status')
    - second argument is the message type ('text' or 'voice')
    - third argument is callback url
    - fourth argument is method i.e. "GET", "POST")
*/

$connector->addCallback("status", "voice", "http://www.mydomain.com/callback2", "POST");

 /* Delete a callback to a connector
    - first argument is the event type ('incoming', 'incoming_fallback', or 'status')
    - second argument is the message type ('text' or 'voice')

*/

$connector->deleteCallback("status", "voice");

 /* Add multiple callbacks to a connector
    - takes an array of arrays i.e ["status", "voice", "http://www.mydomain.com/callback2", "POST"], ["incoming", "text", "http://www.mydomain.com/callback", "POST"]]
    - subarray's first argument is the event type ('incoming', 'incoming_fallback', or 'status')
    - subarray's second argument is the message type ('text' or 'voice')
    - subarray's third argument is callback url
   - subarray's fourth argument is method i.e. "GET", "POST")
*/

$connector->addCallbacks([["status", "voice", "http://www.mydomain.com/callback2", "POST"], ["incoming", "text", "http://www.mydomain.com/callback", "POST"]]);

/* Delete multiple callbacks from a connector
   - takes an array of arrays i.e ["status", "voice", "http://www.mydomain.com/callback2", "POST"], ["incoming", "text", "http://www.mydomain.com/callback", "POST"]]
   - subarray's first argument is the event type ('incoming', 'incoming_fallback', or 'status')
   - subarray's second argument is the message type ('text' or 'voice')
*/

$connector->deleteCallbacks([["status", "voice", "http://www.mydomain.com/callback2", "POST"], ["incoming", "text", "http://www.mydomain.com/callback", "POST"]]);


/*
 Send a message through a connector. The only difference from sending a normal message
 is that you use connector_id instead of a from_number.
*/

$message = new Message;
$message->body = "Hello world from a Vivial Connect Connector";
$message->connector_id = XXX;
$message->to_number = "+1XXXXXXXXXX";
$message->send();

print(var_dump($message));
