<?php

namespace Router\Request;

use Router\Method\Method;
use Router\Routes\Route;

interface Request
{
    public function method(): Method;

    public function headers(): array;

    public function header(string $header): string;

    public function route(): Route;

    public function body(): string;

    public function uri(): string;

    public function formData(): array;

    public function parameters(): array;

    public function parameter(string $key): string;
}
