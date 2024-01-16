<?php

namespace Router\Request;

use Router\Method\Method;
use Router\Routes\Route;

class Request
{
    private Method $method;
    private Route $route;
    private string $uri;
    private array $parameters;

    public function __construct(Method $method, Route $route, string $uri)
    {
        $this->method = $method;
        $this->route = $route;
        $this->uri = $uri;
        $this->parameters = $route->parameters($this->uri);
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function route(): Route
    {
        return $this->route;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function parameter(string $key): string
    {
        if (!array_key_exists($key, $this->parameters)) throw new RequestException("parameter does not exist for parameter ".$key);
        return $this->parameters[$key];
    }
}
