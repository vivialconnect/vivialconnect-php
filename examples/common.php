<?php

require __DIR__ . '/../vendor/autoload.php';

use VivialConnect\Resources\Resource;

Resource::setCredentialToken(Resource::API_KEY, "");
Resource::setCredentialToken(Resource::API_SECRET, "");
Resource::setCredentialToken(Resource::API_ACCOUNT_ID, "");
Resource::init();
