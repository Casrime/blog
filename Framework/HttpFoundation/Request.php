<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

class Request
{
    public Parameter $query;
    public Parameter $request;

    public Parameter $server;
    public Parameter $session;

    public function __construct()
    {
        $this->query = new Parameter($_GET);
        $this->request = new Parameter($_POST);
        $this->server = new Parameter($_SERVER);
        $this->session = new Parameter($_SESSION);
    }

    /**
     * @throws \Exception
     *
     * Provides server value in lowercase, like request_uri
     * THis will return REQUEST_URI
     */
    public function getDataFromServer(string $value): mixed
    {
        if (!$this->server->has(strtoupper($value))) {
            // TODO - Create Server Exception
            throw new \Exception(sprintf('This server parameter does not exists : %s', $value));
        }

        return $this->server->get(strtoupper($value));
    }
}
