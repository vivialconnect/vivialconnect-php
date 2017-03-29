#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\Configuration;

function getConfiguration($configId)
{
    $config = Configuration::find($configId);
    printf("id = %s\n", $config->id);
    printf("name = %s\n", $config->name);
    printf("phone_number = %s\n", $config->phone_number);
    printf("phone_number_type = %s\n", $config->phone_number_type);
    printf("message_status_callback = %s\n", $config->message_status_callback);
    printf("sms_url = %s\n", $config->sms_url);
    printf("sms_method = %s\n", $config->sms_method);
    printf("sms_fallback_url = %s\n", $config->sms_fallback_url);
    printf("sms_fallback_method = %s\n", $config->sms_fallback_method);
}

function listConfigurations($page = 1, $limit = 20)
{
    $configs = Configuration::all(['page' => $page, 'limit' => $limit]);
    foreach ($configs as $key => $config)
    {
        printf("id = %s\n", $config->id);
        printf("name = %s\n", $config->name);
        printf("phone_number = %s\n", $config->phone_number);
        printf("phone_number_type = %s\n", $config->phone_number_type);
        printf("message_status_callback = %s\n", $config->message_status_callback);
        printf("sms_url = %s\n", $config->sms_url);
        printf("sms_method = %s\n", $config->sms_method);
        printf("sms_fallback_url = %s\n", $config->sms_fallback_url);
        printf("sms_fallback_method = %s\n", $config->sms_fallback_method);
        print("\n");
    }
}

function createConfiguration($name, $phoneNumber, $messageStatusCallback = null,
                             $smsUrl = null, $messageStatusCallback = null, $smsMethod = null,
                             $phoneNumberType = 'local')
{
    $config = new Configuration;
    $config->name = $name;
    $config->phone_number = $phoneNumber;
    $config->phone_number_type = $phoneNumberType;
    $config->message_status_callback = $messageStatusCallback;
    $config->sms_url = $smsUrl;
    $config->message_status_callback = $messageStatusCallback;
    $config->sms_method = $smsMethod;
    $config->save();
}

function deleteConfiguration($configId)
{
    $status = Configuration::delete($configId);
    printf("Configuration %s deleted %s", $configId, $status);
}

function main()
{
    $shortOpts = "";
    $longOpts = array(
        "id:",        // Required id value
        "name:",      // Required name value
        "number:",    // Required phone_number value
        "list",       // No value for list
        "get",        // No value for get
        "create",     // No value for create,
        "delete",     // No value for delete,
    );
    $options = getopt($shortOpts, $longOpts);
    foreach (array_keys($options) as $option) switch ($option) {
        case 'list':
            listConfigurations();
            break;

        case 'create':
            $name = $options['name'];
            $phoneNumber = $options['number'];
            createConfiguration($name, $phoneNumber);
            break;

        case 'get':
            $configId = (int)$options['id'];
            getConfiguration($configId);
            break;

        case 'delete':
            $configId = (int)$options['id'];
            deleteConfiguration($configId);
            break;
    }
}

main();
