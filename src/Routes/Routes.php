<?php

namespace Router\Routes;

use Router\Method\Method;

interface Routes
{
    public function add(Route $route);

    public function route(Method $method, string $path): Route;    
}
