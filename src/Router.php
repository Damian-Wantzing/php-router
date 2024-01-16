<?php

namespace Router;

use Router\Method\Method;
use Router\Request\Request;
use Router\Response\Response;
use Router\Routes\Route;
use Router\Routes\Routes;
use Router\Routes\RoutesException;

class Router
{
    private Routes $routes;

    public function __construct()
    {
        $this->routes = new Routes();
    }

    public function get(string $path, callable $callback, callable ...$middleware)
    {
        $route = new Route(Method::Get, $path, $callback, $middleware);
        $this->routes->add($route);
    }

    public function post(string $path, callable $callback, callable ...$middleware)
    {
        $route = new Route(Method::Post, $path, $callback, $middleware);
        $this->routes->add($route);
    }

    public function put(string $path, callable $callback, callable ...$middleware)
    {
        $route = new Route(Method::Put, $path, $callback, $middleware);
        $this->routes->add($route);
    }

    public function delete(string $path, callable $callback, callable ...$middleware)
    {
        $route = new Route(Method::Delete, $path, $callback, $middleware);
        $this->routes->add($route);
    }

    public function handle()
    {
        try
        {
            $route = $this->routes->route(Method::tryFrom($_SERVER['REQUEST_METHOD']), $_SERVER['REQUEST_URI']);
        } 
        catch (RoutesException $e)
        {
            echo "404"; // TODO: use a 404 page or something similar
            return;
        }

        $request = new Request(Method::tryFrom($_SERVER['REQUEST_METHOD']), $route, $_SERVER['REQUEST_URI']);

        $this->middlewareStack($request, new Response(), $route->middleware());
    }

    private function middlewareStack(Request $request, Response $response, array $middlewares, int $index = 0)
    {
        if (!isset($middlewares[$index]))
        {
            // No more middlewares to run, so we call the handler
            call_user_func($request->route()->callback(), $request, $response);
            return;
        }

        $middleware = $middlewares[$index];

        return $middleware($request, $response, function($request, $response) use ($middlewares, $index)
        {
            return $this->middlewareStack($request, $response, $middlewares, $index + 1);
        });
    }
}
