<?php

namespace Router;

class Router
{
    private Routes $routes;

    public function __construct()
    {
        $this->routes = new Routes();
    }

    public function get(string $path, callable $callback)
    {
        $this->routes->addGet($path, $callback);
    }

    public function post(string $path, callable $callback)
    {
        $this->routes->addPost($path, $callback);
    }

    public function put(string $path, callable $callback)
    {
        $this->routes->addPut($path, $callback);
    }

    public function delete(string $path, callable $callback)
    {
        $this->routes->addDelete($path, $callback);
    }

    public function handle()
    {
        try
        {
            $handlerFunction = $this->routes->route(Method::tryFrom($_SERVER['REQUEST_METHOD']), $_SERVER['REQUEST_URI']);
            call_user_func($handlerFunction);
        } 
        catch (RoutesException $e)
        {
            echo "404";
        }
    }
}
