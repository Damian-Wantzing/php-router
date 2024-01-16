<?php

use Router\Request\Request;
use Router\Router;
use Router\Response\Response;

require_once("vendor/autoload.php");

$router = new Router();

$router->get("/test/{id}/{name}", function()
{
    echo "parameter";
});

$router->get("/redirect", function(Request $request, Response $response)
{
    $response->redirect("/form");
});

$middleware = function(Request $request, Response $response, callable $next)
{
    $response->statuscode(404);

    return $next($request, $response);
};

$router->get("/status", function(Request $request, Response $response)
{
    $response->send("test with middleware");
}, $middleware);

$router->post("/post", function(Request $request)
{
    echo $request->body();
});



$router->handle();

class test
{
    public static function handle()
    {
        echo "class";
    }
}
