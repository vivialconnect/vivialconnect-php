<?php

namespace VivialConnect\Resources;

/**
* For manipulating Log resources
*/

class Log extends Resource
{

    /**
    * List all log resources
    *
    * Required Query Parameters:
    *
    *
    * start_time | Start date and time in ISO 8601 format like YYYYMMDDThhmmssZ. (ISO 8601 format without ‘-‘’ or ‘:’‘)
    *
    * end_time   | End date and time in ISO 8601 format like YYYYMMDDThhmmssZ. (ISO 8601 format without ‘-‘’ or ‘:’‘)
    *
    *
    * Optional Query Parameters:
    *
    *
    * log_type    | The log type as a string. Log-types are typically of the form ITEM_TYPE.ACTION, where ITEM_TYPE is the  
    *             | type of item that was affected and ACTION is what happened to it. For example: message.queued.
    *
    * item_id     | Unique id of item that was affected.
    *    
    * operator_id | Unique id of operator that caused this log.
    *    
    * limit       | Used for pagination: number of log records to return
    *    
    * start_key   | Used for pagination: value of last_key from previous response
    *
    * $logs = Log::all(['limit' => $limit, 'start_time' => $startTime, 'end_time' => $endTime]);
    *
    * @return Collection|boolean
    */
    public static function all(array $queryParams = [], array $headers = [])
    {}

    /**
    * List aggregate log resources
    *
    * Required Query Parameters:
    *
    *
    * start_time      | Start date and time in ISO 8601 format like YYYYMMDDThhmmssZ. (ISO 8601 format without ‘-‘’ or ‘:’‘)
    *
    * end_time        | End date and time in ISO 8601 format like YYYYMMDDThhmmssZ. (ISO 8601 format without ‘-‘’ or ‘:’‘)
    *
    * aggregator_type | Valid values are: minutes, hours, days, months, years
    *
    * Optional Query Parameters:
    *
    *
    * log_type    | The log type as a string. Log-types are typically of the form ITEM_TYPE.ACTION, where ITEM_TYPE is the  
    *             | type of item that was affected and ACTION is what happened to it. For example: message.queued.
    *
    * item_id     | Unique id of item that was affected.
    *
    * operator_id | Unique id of operator that caused this log.
    *
    * limit       | Used for pagination: number of log records to return
    *
    * start_key   | Used for pagination: value of last_key from previous response
    *
    * $logs = Log::aggregate(['limit' => $limit, 'start_time' => $startTime, 'end_time' => $endTime, 
    *                         'aggregator_type' => $aggregatorType]);
    *
    * @return Collection|boolean
    */
    public static function aggregate(array $queryParams = [], array $headers = [])
    {}


}