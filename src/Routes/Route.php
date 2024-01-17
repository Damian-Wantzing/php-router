<?php

namespace Router\Routes;

use Router\Method\Method;
use Router\Middlewares\Middlewares;

interface Route
{
    public function method(): Method;

    public function path(): string;

    public function middleware(): Middlewares;

    public function callback(): callable;

    public function parameters(string $uri): array;
}
