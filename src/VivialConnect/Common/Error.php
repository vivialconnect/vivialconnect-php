<?php

namespace VivialConnect\Common;


class Error
{
    /** @var Response  */
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the error message returned by the API
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->response->getStatusPhrase();
    }
}
