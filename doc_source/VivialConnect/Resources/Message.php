<?php

namespace VivialConnect\Resources;

/**
* For manipulating Message resources
*/

class Message extends Resource
{
    /**
    * Alias for ->save()
    *
    * $message = new Message; <br>
    * $message->body = $body; <br>
    * $message->from_number = $fromNumber; <br>
    * $message->to_number = $toNumber; <br>
    * $message->send(); <br>
    *
    * @param array $queryParams
    * @param array $headers
    *
    * @return bool
    */
    public function send(array $queryParams = [], array $headers = [])
    {
    }

    /**
    * $message>getAttachment(1);
    *
    * @param integer|string|null $attachmentId
    * @param array $queryParams
    * @param array $headers
    *
    * @return Attachment|bool
    */
    public function getAttachment($attachmentId = null, array $queryParams = [], array $headers = [])
    {   
    }

    /**
    * $message->getAttachments()
    *
    * @param array $queryParams
    * @param array $headers
    *
    * @return Collection|bool
    */
    public function getAttachments(array $queryParams = [], array $headers = [])
    {
    }

    /**
    * $message->getAttachmentsCount();
    * @param array $queryParams
    * @param array $headers
    *
    * @return integer
    */
    public function getAttachmentsCount(array $queryParams = [], array $headers = [])
    {
    }
}
