<?php

namespace VivialConnect\Resources;

/**
* For manipulating Number resources
*/

class Number extends Resource
{
    /**
    * Alias of ->save()
    *
    * $number = new Number; <br>
    * $number->name = $name; <br>
    * $number->phone_number = $phoneNumber; <br>
    * $number->area_code = $areaCode; <br>
    * $number->phone_number_type = $phoneNumberType; <br>
    * $number->buy(); <br>
    *
    * @param array $queryParams 
    * @param array $headers - additional headers.
    *
    * @return bool
    */
    public function buy(array $queryParams = [], array $headers = [])
    {
        return $this->save($queryParams, $headers);
    }

    /**
    * like ->save() but hardcodes phone_number_type to local
    *
    * $number = new Number; <br>
    * $number->name = $name; <br>
    * $number->phone_number = $phoneNumber; <br>
    * $number->area_code = $areaCode; <br>
    * $number->buy(); <br>
    *
    * @param array
    * @param array $headers - additional headers.
    *
    * @return bool
    */
    public function buyLocal(array $queryParams = [], array $headers = [])
    {
        $this->phone_number_type = 'local';
        return $this->save($queryParams, $headers);
    }

    /**
    * Lists available phone numbers.
    *
    * You must specify exactly one of the following three parameters: in_region, area_code,
    * in_postal_code  
    *
    *                                                                
    * in_region      | String | Filters the results include only phone numbers in a specified 2-letter region (US state).
    *    
    * area_code      | integer | Filters the results to include only phone numbers by US area code.
    *    
    * in_postal_code | integer | Filters the results to include only phone numbers in a specified 5-digit postal code.
    *
    *    
    * Optional parameters:
    *
    *
    * limit          | integer | Number of results to return per page. Default value: 50.
    *
    * contains       | String | Filters the results to include only phone numbers that match a number pattern you specify. The * pattern can include letters, digits, and the following wildcard characters:
    *
    *                            -- ? : matches any single digit
    *    
    *                            -- * : matches zero or more digits
    *
    * local_number   | integer | Filters the results to include only phone numbers that match the first three or more digits
    * you specify to immediately follow the area code. To use this parameter, you must also specify an area_code.
    *
    * in_city        | String | Filters the results to include only phone numbers in a specified city.
    *
    *
    * $qs = ['page' => 1, 'limit' => 20, 'area_code' => 608 ];
    *
    * $numbers = Number::searchAvailable($countryCode, $phoneNumberType, $qs);
    *
    * @param string $countryCode - defaults to 'US'.
    * @param string $phoneNumberType - defaults to 'local'.
    * @param array $queryParams - must contain exactly one of the following three keys: in_region, area_code, in_postal_code.
    * @param array $headers - additional headers.
    *
    * @return Collection - a list of available US local (non-toll-free) phone numbers
    */
    public static function searchAvailable($countryCode = 'US', $phoneNumberType = 'local', array $queryParams = [], array $headers = [])
    {}

    /**
    * Alias of ::destroy()
    * 
    * Number::destroy(1)
    *
    * @param integer|string|null $numberId
    * @param array $queryParams
    * @param array $headers - additional headers.
    *
    * @return Collection - a list of available US local (non-toll-free) phone numbers
    */
    public static function release($numberId, array $queryParams = [], array $headers = [])
    {}
}