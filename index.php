<?php

use Router\Router;

require_once("vendor/autoload.php");

$router = new Router();

$router->get("/", function()
{
    echo "test";
});

$router->get("/test", function()
{
    echo "hello";
});

$router->handle();
