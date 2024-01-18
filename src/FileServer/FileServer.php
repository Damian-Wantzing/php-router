<?php

namespace Router\FileServer;

use Directory;
use Router\Request\Request;
use Router\Response\Response;

class FileServer
{
    private Directory $root;

    public function __construct(Directory $root)
    {
        $this->root = $root;
    }

    public function handleRequest(Request $request, Response $response)
    {
        echo $request->uri();
        echo $request->route()->path();
    }
}
