<?php

namespace VivialConnect\Resources;

use VivialConnect\Common\ResponseException;


class Message extends Resource
{
    public function send(array $queryParams = [], array $headers = [])
    {
        return $this->save($queryParams, $headers);
    }

    public function getAttachment($attachmentId = null, array $queryParams = [], array $headers = [])
    {   
        $instance = new Attachment();
        $response = $instance->getConnection()->get($this->getAttachmentResourceUri($attachmentId), $queryParams, $headers);
        if ($response->isSuccessful()) {
            $instance->response = $response;
            $data = $instance->parseFind($response->getPayload());
            $instance->hydrate($data);
            return $instance;
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }

    public function getAttachments(array $queryParams = [], array $headers = [])
    {
        $className = Attachment::class;
        $instance = new $className;
        $accountId = Resource::getCredentialToken(Resource::API_ACCOUNT_ID);
        $response = $instance->getConnection()->get($this->getAttachmentResourceUri(null), $queryParams, $headers);
        if ($response->isSuccessful()) {
            $data = $instance->parseAll($response->getPayload());
            return new Collection($className, $data, $response);
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }

    public function getAttachmentsCount(array $queryParams = [], array $headers = [])
    {
        $instance = new Attachment();
        $accountId = Resource::getCredentialToken(Resource::API_ACCOUNT_ID);
        $response = $instance->getConnection()->get($this->getAttachmentResourceUri(null, true), $queryParams, $headers);
        if ($response->isSuccessful()) {
            $data = $instance->parseFind($response->getPayload());
            return $data;
        }
        if ($response->isThrowable()) {
            $errorClass = $instance->getConnection()->getErrorClass();
            throw new ResponseException(new $errorClass($response));
        }
        return false;
    }

    private function getAttachmentResourceUri($attachmentId = null, $isCount = false)
    {
        $uri = sprintf(self::API_ACCOUNT_PREFIX,
            self::getCredentialToken(self::API_ACCOUNT_ID));
        $uri .= $this->getResourceName();
        $id = $this->getId();
        $uri .= "/{$id}/attachments";
        if ($attachmentId) {
            $uri .= "/{$attachmentId}";
        }
        if ($isCount) {
            $uri .= "/count";
        }
        $uri .= ".json";
        return $uri;
    }
}
