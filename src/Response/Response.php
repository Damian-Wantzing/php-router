<?php

namespace Router\Response;

interface Response
{
    public function setHeader(string $header, string $value);

    public function statuscode(int $code);

    public function send(string $content);

    public function redirect(string $uri);
}
