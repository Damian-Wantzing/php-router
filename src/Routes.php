<?php

namespace Router;

// TODO: check if there is overlap when adding the paths
class Routes
{
    private array $getRoutes;
    private array $postRoutes;
    private array $putRoutes;
    private array $deleteRoutes;

    public function __construct()
    {
        $this->getRoutes = array();
        $this->postRoutes = array();
        $this->putRoutes = array();
        $this->deleteRoutes = array();
    }

    public function addGet(string $path, callable $callback)
    {
        $this->getRoutes[$path] = $callback;
    }

    public function addPost(string $path, callable $callback)
    {
        $this->postRoutes[$path] = $callback;   
    }

    public function addPut(string $path, callable $callback)
    {
        $this->putRoutes[$path] = $callback;   
    }

    public function addDelete(string $path, callable $callback)
    {
        $this->deleteRoutes[$path] = $callback;   
    }

    public function route(Method $method, string $path): callable
    {
        switch ($method)
        {
            case Method::Get:
                return $this->routeGet($path);
            case Method::Post:
                return $this->routePost($path);
            case Method::Put:
                return $this->routePut($path);
            case Method::Delete:
                return $this->routeDelete($path);
        }
    }

    private function routeGet(string $path): callable
    {
        if (!array_key_exists($path, $this->getRoutes)) throw new RoutesException("route not found");
        return $this->getRoutes[$path];
    }

    private function routePost(string $path): callable
    {
        if (!(array_key_exists($path, $this->postRoutes))) throw new RoutesException("route not found");
        return $this->postRoutes[$path];
    }

    private function routePut(string $path): callable
    {
        if (!array_key_exists($path, $this->putRoutes)) throw new RoutesException("route not found");
        return $this->putRoutes[$path];
    }

    private function routeDelete(string $path): callable
    {
        if (!array_key_exists($path, $this->deleteRoutes)) throw new RoutesException("route not found");
        return $this->deleteRoutes[$path];
    }
}
