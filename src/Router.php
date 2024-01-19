<?php

namespace Router;

use Router\FileServer\FileServerException;
use Router\Method\Method;
use Router\Middlewares\Middlewares;
use Router\Request\HttpRequest;
use Router\Request\Request;
use Router\Response\HttpResponse;
use Router\Response\Response;
use Router\Routes\HttpRoute;
use Router\Routes\HttpRoutes;
use Router\Routes\Routes;
use Router\Routes\RoutesException;

class Router
{
    private Routes $routes;
    private Middlewares $middlewares;
    private $notFoundHandler;

    public function __construct()
    {
        $this->routes = new HttpRoutes();
        $this->middlewares = new Middlewares();
        $this->notFoundHandler = [$this, 'notFoundHandler'];
    }

    public function notFound(callable $callback)
    {
        $this->notFoundHandler = $callback;
    }

    private function notFoundHandler()
    {
        echo "not found";
    }

    public function get(string $path, callable $callback, callable|Middlewares ...$middlewares)
    {
        $mergedMiddlewares = array();
        foreach ($middlewares as $middleware)
        {
            if ($middleware instanceof Middlewares) array_push($mergedMiddlewares, ...$middleware->toArray());
            else array_push($mergedMiddlewares, $middleware);
        }

        $route = new HttpRoute(Method::Get, $path, $callback, new Middlewares(...$mergedMiddlewares));
        $this->routes->add($route);
    }

    public function post(string $path, callable $callback, callable ...$middleware)
    {
        $route = new HttpRoute(Method::Post, $path, $callback, new Middlewares(...$middleware));
        $this->routes->add($route);
    }

    public function put(string $path, callable $callback, callable ...$middleware)
    {
        $route = new HttpRoute(Method::Put, $path, $callback, new Middlewares(...$middleware));
        $this->routes->add($route);
    }

    public function delete(string $path, callable $callback, callable ...$middleware)
    {
        $route = new HttpRoute(Method::Delete, $path, $callback, new Middlewares(...$middleware));
        $this->routes->add($route);
    }

    public function use(callable $middleware)
    {
        $this->middlewares->add($middleware);
    }

    public function handle()
    {
        try
        {
            $route = $this->routes->route(Method::tryFrom($_SERVER['REQUEST_METHOD']), $_SERVER['REQUEST_URI']);
        } 
        catch (RoutesException $e)
        {
            call_user_func($this->notFoundHandler);
            return;
        }

        $request = new HttpRequest(Method::tryFrom($_SERVER['REQUEST_METHOD']), $route, $_SERVER['REQUEST_URI']);

        list($request, $response) = $this->middlewareStack(
            $request, 
            new HttpResponse(), 
            array_merge($this->middlewares->toArray(), $route->middleware()->toArray())
        );

        try
        {
            call_user_func($route->callback(), $request, $response);
        }
        catch (FileServerException $e)
        {
            call_user_func($this->notFoundHandler);
            return;
        }
        
    }

    private function middlewareStack(Request $request, Response $response, array $middlewares, int $index = 0): array
    {
        if (!isset($middlewares[$index]))
        {
            // No more middlewares to run, so we return
            return array($request, $response);
        }

        $middleware = $middlewares[$index];

        return $middleware($request, $response, function($request, $response) use ($middlewares, $index)
        {
            return $this->middlewareStack($request, $response, $middlewares, $index + 1);
        });
    }
}
