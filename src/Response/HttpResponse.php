<?php
namespace Router\Response;

class HttpResponse implements Response
{
    public function setHeader(string $header, string $value)
    {
        header("{$header}: {$value}");
    }

    public function statuscode(int $code)
    {
        http_response_code($code);
    }

    public function send(string $content)
    {
        echo($content);
    }

    public function redirect(string $uri)
    {
        $this->statuscode(301);
        $this->setHeader("Location", $uri);
    }
}
