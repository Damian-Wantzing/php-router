<?php

use Router\FileServer\FileServer;
use Router\Middlewares\Middlewares;
use Router\Request\Request;
use Router\Router;
use Router\Response\Response;

require_once("vendor/autoload.php");

$router = new Router();

$router->use(function(Request $request, Response $response, callable $next)
{
    echo "global middelware";

    return $next($request, $response);
});

$middleware1 = function(Request $request, Response $response, callable $next)
{
    $response->statuscode(400);

    return $next($request, $response);
};

$middleware2 = function(Request $request, Response $response, callable $next)
{
    $response->setHeader("Access-Control-Allow-Origin", "*");

    return $next($request, $response);
};

$middleware3 = function(Request $request, Response $response, callable $next)
{
    $response->send("hello, from third middleware");

    return $next($request, $response);
};

$middlewares = new Middlewares($middleware1, $middleware2, $middleware3);

$middleware4 = function(Request $request, Response $response, callable $next)
{

    echo "<br>this middleware in run after the middlewares";

    return $next($request, $response);
};

$router->get("/status/{test}", function(Request $request, Response $response)
{
    $response->send("test with middleware");
}, $middlewares, $middleware4);

$router->get("/view/{id}", function(Request $request, Response $response)
{
    $test = "test string";

    ob_start();
    require(__DIR__."/templates/home.html");
    $view = ob_get_clean();
    
    $response->send($view);
});

$router->post("/post", function(Request $request)
{
    echo $request->body();
});

$fileserver = new FileServer(dir(__DIR__."/static"));

$router->get("/static", [$fileserver, 'handleRequest']);

$router->handle();
