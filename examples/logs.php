#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\Log;

function listLogs($startTime = '20170101T150000Z', $endTime = '20170325T175959Z', $limit = 10)
{
    $logs = Log::all(['limit' => $limit, 'start_time' => $startTime, 'end_time' => $endTime]);
    foreach ($logs as $key => $log)
    {
        printf("account_id = %s\n", $log->account_id);
        printf("description = %s\n", $log->description);
        printf("operator_type = %s\n", $log->operator_type);
        print("log_data = {\n");
        print_r($log->log_data);
        print("}\n");
        print("\n");
    }
}

function listAggregateLogs($startTime = '20170101T150000Z', $endTime = '20170325T175959Z', $aggregatorType = 'days', $limit = 10)
{
    $logs = Log::aggregate(['limit' => $limit, 'start_time' => $startTime,
        'end_time' => $endTime, 'aggregator_type' => $aggregatorType]);
    foreach ($logs as $key => $log)
    {
        printf("account_id = %s\n", $log->account_id);
        printf("account_id_log_type = %s\n", $log->account_id_log_type);
        printf("aggregate_key = %s\n", $log->aggregate_key);
        printf("log_count = %s\n", $log->log_count);
        printf("log_timestamp = %s\n", $log->log_timestamp);
        printf("log_type = %s\n", $log->log_type);
        print("\n");
    }
}

function main()
{
    $shortOpts = "";
    $longOpts = array(
        "start:",     // tart_time
        "end:",       // end_time
        "type:",      // aggregator_type
        "list",       // No value for list
        "aggregate",  // No value for aggregate,
    );
    $options = getopt($shortOpts, $longOpts);
    foreach (array_keys($options) as $option) switch ($option) {
        case 'list':
            $startTime = (!empty($options['start']) ? $options['start'] : '20170101T150000Z');
            $endTime = (!empty($options['end']) ? $options['end'] : '20170325T175959Z');
            listLogs($startTime, $endTime);
            break;

        case 'aggregate':
            $startTime = (!empty($options['start']) ? $options['start'] : '20170101T150000Z');
            $endTime = (!empty($options['end']) ? $options['end'] : '20170325T175959Z');
            $aggregatorType = (!empty($options['type']) ? $options['type'] : 'days');
            listAggregateLogs($startTime, $endTime, $aggregatorType);   
            break;
    }
}

main();
