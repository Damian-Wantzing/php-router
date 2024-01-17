<?php

namespace Router\Middlewares;

class Middlewares
{
    private array $middlewares;

    public function __construct(callable ...$middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function add(callable ...$middlewares)
    {
        array_push($this->middlewares, ...$middlewares);
    }

    public function toArray(): array
    {
        return $this->middlewares;
    }    
}
