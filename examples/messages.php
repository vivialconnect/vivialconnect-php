#!/usr/bin/env php
<?php

require __DIR__ . '/common.php';

use VivialConnect\Resources\Message;

function sendMessage($body = null, $url = null, $fromNumber = null, $toNumber = null)
{
    $message = new Message;
    $message->body = $body;
    if ($url) {
        $message->$media_urls = [$url];
    }
    $message->from_number = $fromNumber;
    $message->to_number = $toNumber;
    $message->send();
}

function getMessage($messageId)
{
    $message = Message::find($messageId);
    printf("id = %s\n", $message->id);
    printf("body = %s\n", $message->body);
    printf("from_number = %s\n", $message->from_number);
    printf("to_number = %s\n", $message->to_number);

    $count = $message->getAttachmentsCount();
    
    printf("Attachment count for message is %s\n", $count);
    if ($count > 0) {
        $attachments = $message->getAttachments(['limit' => 5]);
        foreach ($attachments as $key => $value) {
            $attachment = $message->getAttachment($value->id);
            printf("attachment id = %s\n", $attachment->id);
            print("\n");
        }
    }
}

function listMessages($page = 1, $limit = 20)
{
    $messages = Message::all(['page' => $page, 'limit' => $limit]);
    foreach ($messages as $key => $message)
    {
        printf("id = %s\n", $message->id);
        printf("body = %s\n", $message->body);
        printf("from_number = %s\n", $message->from_number);
        printf("to_number = %s\n", $message->to_number);
        print("\n");
    }
}

function main()
{
    $shortOpts = "";
    $longOpts = array(
        "id:",        // Required id value
        "body:",      // Required body value
        "url:",       // Required media url value
        "from:",      // Required from number value
        "to:",        // Required to number value
        "list",       // No value for list
        "get",        // No value for get
        "send",       // No value for send
    );
    $options = getopt($shortOpts, $longOpts);
    foreach (array_keys($options) as $option) switch ($option) {
        case 'list':
            listMessages();
            break;

        case 'send':
            $body = $options['body'];
            $from = $options['from'];
            $to = $options['to'];
            $url = $options['url'];
            sendMessage($body, $url, $from, $to);
            break;

        case 'get':
            $id = (int)$options['id'];
            getMessage($id);
            break;
    }
}

main();
