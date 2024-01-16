<?php

use Router\Router;

require_once("vendor/autoload.php");

$router = new Router();

$router->get("/test/{id}/{name}", function()
{
    echo "parameter";
});

$router->get("/test/exact/{name}", function()
{
    echo "exact";
});

$router->handle();

class test
{
    public static function handle()
    {
        echo "class";
    }
}
