<?php

namespace Router\Request;

use Router\Method\Method;
use Router\Routes\Route;

class Request
{
    private Method $method;
    private array $headers;
    private string $body;
    private Route $route;
    private string $uri;
    private array $parameters;

    public function __construct(Method $method, Route $route, string $uri)
    {
        $this->method = $method;
        $this->route = $route;
        $this->uri = $uri;
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function headers(): array
    {
        if (isset($this->headers)) return $this->headers;

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $this->headers[$header] = $value;
            }
        }
        return $this->headers;
    }

    public function header(string $header): string
    {
        if (!array_key_exists($header, $headers = $this->headers())) throw new RequestException("header does not exist for header: ".$header);
        return $headers[$header];
    }

    public function route(): Route
    {
        return $this->route;
    }

    public function body(): string
    {
        if (isset($this->body)) return $this->body;
        return $this->body = file_get_contents('php://input');
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function formData(): array
    {
        return array_merge($_POST, $_GET);
    }

    public function parameters(): array
    {
        if (!isset($this->parameters)) $this->parameters = $this->route()->parameters($this->uri);
        return $this->parameters;
    }

    public function parameter(string $key): string
    {
        if (!array_key_exists($key, $this->parameters())) throw new RequestException("parameter does not exist for parameter ".$key);
        return $this->parameters[$key];
    }
}
