<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

use Framework\Exception\GenericException;

class Request
{
    public Parameter $query;
    public Parameter $request;

    public Parameter $server;
    public Session $session;

    public function __construct()
    {
        $this->query = new Parameter($_GET);
        $this->request = new Parameter($_POST);
        $this->server = new Parameter($_SERVER);
        $this->session = new Session();
    }

    /**
     * @throws GenericException
     *
     * Provides server value in lowercase, like request_uri
     * THis will return REQUEST_URI
     */
    public function getDataFromServer(string $value): mixed
    {
        if (!$this->server->has(strtoupper($value))) {
            throw new GenericException(sprintf('This server parameter does not exists : %s', $value));
        }

        return $this->server->get(strtoupper($value));
    }
}
