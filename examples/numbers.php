#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\Number;

function listAssociatedNumbers($page = 1, $limit = 20)
{
	$numbers = Number::all(['page' => $page, 'limit' => $limit]);
	foreach ($numbers as $key => $number)
	{
	    printf("id = %s\n", $number->id);
		printf("name = %s\n", $number->name);
		printf("phone_number = %s\n", $number->phone_number);
		print("\n");
	}
}

function listAvailableNumbers($countryCode = 'US', $phoneNumberType = 'local',
							  $areaCode = '913', $inPostalCode = null, $inRegion = null,
							  $page = 1, $limit = 20)
{
    $qs = ['page' => $page, 'limit' => $limit];
    if (!empty($areaCode))
    	$qs['area_code'] = $areaCode;
    if (!empty($inPostalCode))
    	$qs['in_postal_code'] = $inPostalCode;
    if (!empty($inRegion))
    	$qs['in_region'] = $inRegion;
	$numbers = Number::searchAvailable($countryCode, $phoneNumberType, $qs);
	foreach ($numbers as $key => $number)
	{
		printf("name = %s\n", $number->name);
		printf("phone_number = %s\n", $number->phone_number);
		printf("phone_number_type = %s\n", $number->phone_number_type);
		print("\n");
	}
}

function buyNumber($name = null, $phoneNumber = null,
				   $areaCode = null, $phoneNumberType = 'local')
{
	$number = new Number;
	$number->name = $name;
    $number->phone_number = $phoneNumber;
    $number->area_code = $areaCode;
    $number->phone_number_type = $phoneNumberType;
    $number->buy();

    printf("Acquired number id %s\n", $number->id);
    printf("name = %s\n", $number->name);
	printf("phone_number = %s\n", $number->phone_number);
	printf("phone_number_type = %s\n", $number->phone_number_type);
}

function updateNumber($numberId, $name = null)
{
	$number = Number::find($numberId);
	$number->name = $name;
    $number->save();

    printf("id = %s\n", $number->id);
	printf("name = %s\n", $number->name);
}

function getNumber($numberId)
{
	$number = Number::find($numberId);

	printf("id = %s\n", $number->id);
	printf("name = %s\n", $number->name);
	printf("phone_number = %s\n", $number->phone_number);
}

function releaseNumber($numberId)
{
	$status = Number::release($numberId);
	printf("Number %s released %s", $numberId, $status);
}

function main()
{
	$shortOpts = "";
	$longOpts = array(
	    "id:",        // Required id value
	    "name:",      // Required company_name value
	    "number:",      // Required phone_number value
	    "area:",      // Required area_code value
	    "available",       // No value for available numbers list
	    "associated",       // No value for associated numbers list
	    "get",        // No value for get
	    "buy",        // No value for buy
	    "release",     // Release phone number
	    "update",     // No value for update,
	);
	$options = getopt($shortOpts, $longOpts);
	foreach (array_keys($options) as $option) switch ($option) {
		case 'associated':
			listAssociatedNumbers();
	    	break;

	    case 'available':
	    	$areaCode = $options['area'];
			listAvailableNumbers('US', 'local', $areaCode);
	    	break;

	    case 'update':
	    	$name = $options['name'];
	    	$id = (int)$options['id'];
	    	updateNumber($id, $name);
	    	break;

	    case 'get':
	    	$id = (int)$options['id'];
	    	getNumber($id);
	    	break;

	    case 'buy':
	    	$name = $options['name'];
	    	$phoneNumber = $options['number'];
	    	$areaCode = $options['area'];
	    	buyNumber($name, $phoneNumber, $areaCode);
	    	break;

	    case 'release':
	    	$id = (int)$options['id'];
	    	releaseNumber($id);
	    	break;
	}
}

main();
