<?php

namespace Router;

use Router\Method\Method;
use Router\Request\Request;
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

    public function get(string $path, callable $callback)
    {
        $route = new Route(Method::Get, $path, $callback);
        $this->routes->add($route);
    }

    public function post(string $path, callable $callback)
    {
        $route = new Route(Method::Post, $path, $callback);
        $this->routes->add($route);
    }

    public function put(string $path, callable $callback)
    {
        $route = new Route(Method::Put, $path, $callback);
        $this->routes->add($route);
    }

    public function delete(string $path, callable $callback)
    {
        $route = new Route(Method::Delete, $path, $callback);
        $this->routes->add($route);
    }

    public function handle()
    {
        try
        {
            $route = $this->routes->route(Method::tryFrom($_SERVER['REQUEST_METHOD']), $_SERVER['REQUEST_URI']);
            $request = new Request(Method::tryFrom($_SERVER['REQUEST_METHOD']), $route, $_SERVER['REQUEST_URI']);
            call_user_func($route->callback(), $request);
        } 
        catch (RoutesException $e)
        {
            echo "404";
        }
    }
}
