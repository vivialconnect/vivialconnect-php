<?php

$destroy_json = '{}';
$find_json =
'
{
  "message": {
    "account_id": 10012, 
    "body": "Howdy, from Vivial Connect again!", 
    "connector_id": null, 
    "date_created": "2017-05-30T21:09:18+00:00", 
    "date_modified": "2017-05-30T21:10:29+00:00", 
    "direction": "outbound-api", 
    "error_code": null, 
    "error_message": null, 
    "from_number": "+14132134918", 
    "id": 22075, 
    "master_account_id": 10012, 
    "message_type": "local_sms", 
    "num_media": 0, 
    "num_segments": 1, 
    "price": 75, 
    "price_currency": "USD", 
    "sent": "2017-05-30T21:10:07+00:00", 
    "status": "delivered", 
    "to_number": "+16084218409"
  }
}
';

$send_json = 
'
{
  "message": {
    "account_id": 10012, 
    "body": "test message", 
    "connector_id": null, 
    "date_created": "2017-06-01T15:23:30+00:00", 
    "date_modified": "2017-06-01T15:23:30+00:00", 
    "direction": "outbound-api", 
    "error_code": null, 
    "error_message": null, 
    "from_number": "+14132134918", 
    "id": 22123, 
    "master_account_id": 10012, 
    "message_type": "local_sms", 
    "num_media": 0, 
    "num_segments": 1, 
    "price": 75, 
    "price_currency": "USD", 
    "sent": null, 
    "status": "accepted", 
    "to_number": "16084218409"
  }
}
';

$all_json=
'
{
  "messages": [
    {
      "account_id": 10110, 
      "body": "Hello World!", 
      "connector_id": null, 
      "date_created": "2017-04-26T21:37:49+00:00", 
      "date_modified": "2017-04-26T21:38:12+00:00", 
      "direction": "outbound-api", 
      "error_code": null, 
      "error_message": null, 
      "from_number": "+16126017532", 
      "id": 21718, 
      "master_account_id": 10110, 
      "message_type": "local_sms", 
      "num_media": 0, 
      "num_segments": 1, 
      "price": 75, 
      "price_currency": "USD", 
      "sent": "2017-04-26T21:38:08+00:00", 
      "status": "delivered", 
      "to_number": "+16084218409"
    }, 
    {
      "account_id": 10110, 
      "body": "Hello World!", 
      "connector_id": null, 
      "date_created": "2017-04-27T19:33:55+00:00", 
      "date_modified": "2017-04-27T19:34:05+00:00", 
      "direction": "outbound-api", 
      "error_code": null, 
      "error_message": null, 
      "from_number": "+14132062246", 
      "id": 21729, 
      "master_account_id": 10110, 
      "message_type": "local_sms", 
      "num_media": 0, 
      "num_segments": 1, 
      "price": 75, 
      "price_currency": "USD", 
      "sent": "2017-04-27T19:34:00+00:00", 
      "status": "delivered", 
      "to_number": "+16084218409"
    }, 
    {
      "account_id": 10110, 
      "body": "counting total messages", 
      "connector_id": null, 
      "date_created": "2017-04-28T13:16:22+00:00", 
      "date_modified": "2017-04-28T13:16:41+00:00", 
      "direction": "outbound-api", 
      "error_code": null, 
      "error_message": null, 
      "from_number": "+14132062246", 
      "id": 21739, 
      "master_account_id": 10110, 
      "message_type": "local_sms", 
      "num_media": 0, 
      "num_segments": 1, 
      "price": 75, 
      "price_currency": "USD", 
      "sent": "2017-04-28T13:16:27+00:00", 
      "status": "delivered", 
      "to_number": "+16084218409"
    }, 
    {
      "account_id": 10110, 
      "body": "counting total messages2", 
      "connector_id": null, 
      "date_created": "2017-04-28T19:36:31+00:00", 
      "date_modified": "2017-04-28T19:37:11+00:00", 
      "direction": "outbound-api", 
      "error_code": null, 
      "error_message": null, 
      "from_number": "+14132062246", 
      "id": 21746, 
      "master_account_id": 10110, 
      "message_type": "local_sms", 
      "num_media": 0, 
      "num_segments": 1, 
      "price": 75, 
      "price_currency": "USD", 
      "sent": "2017-04-28T19:37:05+00:00", 
      "status": "delivered", 
      "to_number": "+16084218409"
    }, 
    {
      "account_id": 10110, 
      "body": "counting total messages3", 
      "connector_id": null, 
      "date_created": "2017-05-01T13:34:04+00:00", 
      "date_modified": "2017-05-01T13:34:22+00:00", 
      "direction": "outbound-api", 
      "error_code": null, 
      "error_message": null, 
      "from_number": "+14132062246", 
      "id": 21761, 
      "master_account_id": 10110, 
      "message_type": "local_sms", 
      "num_media": 0, 
      "num_segments": 1, 
      "price": 75, 
      "price_currency": "USD", 
      "sent": "2017-05-01T13:34:10+00:00", 
      "status": "delivered", 
      "to_number": "+16084218409"
    }, 
    {
      "account_id": 10110, 
      "body": "Hello world!", 
      "connector_id": null, 
      "date_created": "2017-05-04T13:28:29+00:00", 
      "date_modified": "2017-05-04T13:28:36+00:00", 
      "direction": "outbound-api", 
      "error_code": null, 
      "error_message": null, 
      "from_number": "+16469025894", 
      "id": 21800, 
      "master_account_id": 10110, 
      "message_type": "local_sms", 
      "num_media": 0, 
      "num_segments": 1, 
      "price": 75, 
      "price_currency": "USD", 
      "sent": "2017-05-04T13:28:32+00:00", 
      "status": "delivered", 
      "to_number": "+16084218409"
    }
  ]
}
';

$attachment_json = 
'
{
  "attachment": {
    "account_id": 10110, 
    "content_type": "image/jpeg", 
    "date_created": "2017-06-01T17:47:45+00:00", 
    "date_modified": "2017-06-01T17:47:45+00:00", 
    "file_name": "jea-0007-mn-united-vs-bournemouth.jpg", 
    "id": 264, 
    "key_name": "mms/bd/ff5aab3cdd0f789707ee9af65c50fc9f244398/jea-0007-mn-united-vs-bournemouth.jpg", 
    "message_id": 22124, 
    "size": 102094
  }
}
';

$attachments_json = 
'
{
  "attachments": [
    {
      "account_id": 10110, 
      "content_type": "image/jpeg", 
      "date_created": "2017-06-01T18:46:58+00:00", 
      "date_modified": "2017-06-01T18:46:58+00:00", 
      "file_name": "20140612__6-12NEWChristianRamirez.jpg", 
      "id": 265, 
      "key_name": "mms/93/7a4b74a1d9c615b4cabc36b3bbfab707b188c2/20140612__6-12NEWChristianRamirez.jpg", 
      "message_id": 22125, 
      "size": 61018
    }, 
    {
      "account_id": 10110, 
      "content_type": "image/jpeg", 
      "date_created": "2017-06-01T18:46:59+00:00", 
      "date_modified": "2017-06-01T18:46:59+00:00", 
      "file_name": "CR18.jpg", 
      "id": 266, 
      "key_name": "mms/6e/6f36aa0133fb0777f700054fc652871cda6ca3/CR18.jpg", 
      "message_id": 22125, 
      "size": 1250788
    }, 
    {
      "account_id": 10110, 
      "content_type": "image/jpeg", 
      "date_created": "2017-06-01T18:47:00+00:00", 
      "date_modified": "2017-06-01T18:47:00+00:00", 
      "file_name": "christian1.jpg", 
      "id": 267, 
      "key_name": "mms/11/de99e3e01ba1b3b99999b4b6055738f654b52a/christian1.jpg", 
      "message_id": 22125, 
      "size": 45146
    }
  ]
}
';