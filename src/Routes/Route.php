<?php

namespace Router\Routes;

use Router\Method\Method;
use Router\Middlewares\Middlewares;

class Route
{
    private Method $method;
    private string $path;
    private Middlewares $middlewares;
    private $callback;

    public function __construct(Method $method, string $path, callable $callback, Middlewares $middlewares)
    {
        $this->method = $method;
        $this->path = $path;
        $this->callback = $callback;
        $this->middlewares = $middlewares;
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function middleware(): Middlewares
    {
        return $this->middlewares;
    }

    public function callback(): callable
    {
        return $this->callback;
    }

    public function parameters(string $uri): array
    {
        $uriParts = explode("/", $uri);
        $pathParts = explode("/", $this->path);

        $parameters = array();

        for ($i = 0; $i < count($pathParts); $i++)
        {
            if (!preg_match("/{.*}/", $pathParts[$i])) continue;
            $parameter = substr($pathParts[$i], 1, strlen($pathParts[$i]) - 2);
            $parameters[$parameter] = $uriParts[$i];
        }

        return $parameters;
    }
}
