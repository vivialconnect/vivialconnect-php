<?php

namespace VivialConnect\Common;


class ResponseException extends \RuntimeException
{
    protected $error = null;

    public function __construct(Error $error)
    {
        parent::__construct($error->getMessage(), $error->getResponse()->getStatusCode(), null);

        $this->error = $error;
    }

    public function getError()
    {
        return $this->error;
    }
}
