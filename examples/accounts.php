#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\Account;

function getAccount($accountId)
{
    $account = Account::find($accountId);
    printf("id = %s\n", $account->id);
    printf("company_name = %s\n", $account->company_name);
}

function listSubaccounts($page = 1, $limit = 20)
{
    $accounts = Account::subAccounts(['page' => $page, 'limit' => $limit]);
    foreach ($accounts as $key => $account)
    {
        printf("id = %s\n", $account->id);
        printf("company_name = %s\n", $account->company_name);
        print("\n");
    }
}

function updateAccount($accountId, $companyName)
{
    $account = Account::find($accountId);
    $account->company_name = $companyName;
    $account->save();
}

function billingStatus($accountId)
{
    $status = Account::billingStatus($accountId);
    printf("id = %s\n", $status->id);
    printf("free_trial = %s\n", $status->free_trial);
    printf("free_trial_max_numbers = %s\n", $status->free_trial_max_numbers);
    printf("free_trial_purchased_count = %s\n", $status->free_trial_purchased_count);
}

function createSubaccount($companyName)
{
    $account = new Account;
    $account->company_name = $companyName;
    $account->save();
}

function deleteSubaccount($accountId)
{
    $account = Account::find($accountId);
    $account->destroy();
}

function main()
{
    $shortOpts = "";
    $longOpts = array(
        "id:",        // Required id value
        "name:",      // Required company_name value
        "list",       // No value for list
        "get",        // No value for get
        "create",     // No value for create,
        "update",     // No value for update,
        "status",     // No value for billing status
        "delete",     // No value for delete
    );
    $options = getopt($shortOpts, $longOpts);
    foreach (array_keys($options) as $option) switch ($option) {
        case 'list':
            listSubaccounts();
            break;

        case 'update':
            $companyName = $options['name'];
            $accountId = (int)$options['id'];
            updateAccount($accountId, $companyName);
            break;

        case 'create':
            $companyName = $options['name'];
            createSubaccount($companyName);
            break;

        case 'get':
            $accountId = (int)$options['id'];
            getAccount($accountId);
            break;

        case 'status':
            $accountId = $options['id'];
            billingStatus($accountId);
            break;

        case 'delete':
            $accountId = (int)$options['id'];
            deleteSubaccount($accountId);
            break;
    }
}

main();
