#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\User;

function getUser($userId)
{
    $user = User::find($userId);
    printf("id = %s\n", $user->id);
    printf("first_name = %s\n", $user->first_name);
    printf("last_name = %s\n", $user->last_name);
}

function listUsers($page = 1, $limit = 20)
{
    $users = User::all(['page' => $page, 'limit' => $limit]);
    foreach ($users as $key => $user)
    {
        printf("id = %s\n", $user->id);
        printf("first_name = %s\n", $user->first_name);
        printf("last_name = %s\n", $user->last_name);
        print("\n");
    }
}

function updateUser($userId, $firstName = null, $lastName = null)
{
    $user = User::find($userId);
    $user->first_name = $firstName;
    $user->last_name = $lastName;
    $user->save();
}

function main()
{
    $shortOpts = "";
    $longOpts = array(
        "id:",        // Required id value
        "fname:",     // Required first_name value
        "lname:",     // Required last_name value
        "list",       // No value for list
        "get",        // No value for get
        "update",     // No value for update,
    );
    $options = getopt($shortOpts, $longOpts);
    foreach (array_keys($options) as $option) switch ($option) {
        case 'list':
            listUsers();
            break;

        case 'update':
            $firstName = $options['fname'];
            $lastName = $options['lname'];
            $userId = (int)$options['id'];
            updateUser($userId, $firstName, $lastName);
            break;

        case 'get':
            $userId = (int)$options['id'];
            getUser($userId);
            break;
    }
}

main();
