#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use VivialConnect\Resources\Message;
use VivialConnect\Resources\Account;
use VivialConnect\Resources\Number;
use VivialConnect\Resources\User;
use VivialConnect\Resources\Log;
use VivialConnect\Resources\Resource;
use VivialConnect\Transport\Connection;

Resource::setCredentialToken(Resource::API_KEY, "");
Resource::setCredentialToken(Resource::API_SECRET, "");
Resource::setCredentialToken(Resource::API_ACCOUNT_ID, "");

$options = [
    Connection::OPTION_LOG => true
];
Resource::init($options);

function getLoggingExample()
{
    $messages = Message::all(['page' => 1, 'limit' => 5]);
    $connection = Resource::getConnectionByName('default');
    $log = $connection->getLog();
    print_r($log);
}

getLoggingExample();
