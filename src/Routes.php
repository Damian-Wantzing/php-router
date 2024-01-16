<?php

namespace Router;

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

    public function add(Route $route)
    {
        switch ($route->method())
        {
            case Method::Get:
                if (array_key_exists($route->path(), $this->getRoutes)) throw new RoutesException("Route already exists for path: " . $route->path());
                $this->getRoutes[$route->path()] = $route;
                break;
            case Method::Post:
                if (array_key_exists($route->path(), $this->postRoutes)) throw new RoutesException("Route already exists for path: " . $route->path());
                $this->postRoutes[$route->path()] = $route;
                break;
            case Method::Put:
                if (array_key_exists($route->path(), $this->putRoutes)) throw new RoutesException("Route already exists for path: " . $route->path());
                $this->putRoutes[$route->path()] = $route;
                break;
            case Method::Delete:
                if (array_key_exists($route->path(), $this->deleteRoutes)) throw new RoutesException("Route already exists for path: " . $route->path());
                $this->deleteRoutes[$route->path()] = $route;
                break;
        }
    }

    public function route(Method $method, string $path): Route
    {
        switch ($method)
        {
            case Method::Get:
                $path = $this->determinePath($this->getRoutes, $path);
                return $this->getRoutes[$path];
            case Method::Post:
                $path = $this->determinePath($this->postRoutes, $path);
                return $this->postRoutes[$path];
            case Method::Put:
                $path = $this->determinePath($this->putRoutes, $path);
                return $this->putRoutes[$path];
            case Method::Delete:
                $path = $this->determinePath($this->deleteRoutes, $path);
                return $this->deleteRoutes[$path];
        }
    }

    private function determinePath(array $paths, string $pathToCheck): string
    {
        // trim trailing slash
        $pathToCheck = rtrim($pathToCheck, '/');
        
        // is there an exact match?
        if (array_key_exists($pathToCheck, $paths)) return $pathToCheck;
        
        // split the path into parts
        $matchingPaths = $this->findMatchingPaths($paths, $pathToCheck);

        // check if we have no matches, if so, throw an exception
        if (count($matchingPaths) < 1) throw new RoutesException("No route found for path: " . $pathToCheck);
        
        // if there is only one matching part return the part
        if (count($matchingPaths) == 1) return $matchingPaths[0];

        // if there are multiple matching parts, find the best one    
        return $this->findBestPath($matchingPaths);
    }

    private function findMatchingPaths(array $paths, string $pathToCheck): array
    {
        $matchingPaths = array();

        foreach ($paths as $storedPath => $route)
        {
            // are the parts not the same length?
            if (count(explode('/', $storedPath)) != count(explode('/', $pathToCheck))) continue;
            if (!$this->doPathsMatch($storedPath, $pathToCheck)) continue;

            // all parts match
            $matchingPaths[] = $storedPath;
        }

        return $matchingPaths;
    }

    private function doPathsMatch(string $storedPath, string $pathToCheck): bool
    {
        $storedPathParts = explode('/', $storedPath);
        $pathToCheckParts = explode('/', $pathToCheck);

        for ($i = 0; $i < count($storedPathParts); $i++)
        {
            if ($storedPathParts[$i] != $pathToCheckParts[$i] && !$this->isParameter($storedPathParts[$i])) return false;
        }

        return true;
    }

    /**
     * Find the best path from a list of paths
     * @param array $paths 
     * @return mixed string
     */
    private function findBestPath(array $paths): string
    {
        // Initialize the best path to the first one
        $bestPath = $paths[0];

        for ($i = 1; $i < count($paths); $i++)
        {
            $bestPath = $this->bestPath($bestPath, $paths[$i]);
        }
    
        return $bestPath;
    }

    /**
     * Determine which path is the best out of two paths
     * @param string $firstPath 
     * @param string $secondPath 
     * @return string 
     */
    private function bestPath(string $firstPath, string $secondPath): string
    {
        $bestPathParts = explode("/", $firstPath);
        $pathParts = explode("/", $secondPath);

        for ($j = 0; $j < count($pathParts); $j++)
        {
            if ($this->isParameter($bestPathParts[$j]) && !$this->isParameter($pathParts[$j]))
            {
                return $secondPath;
            }
            else if (!$this->isParameter($bestPathParts[$j]) && $this->isParameter($pathParts[$j]))
            {
                return $firstPath;
            }
            else continue;
        }

        return $firstPath;
    }

    private function isParameter($value): bool
    {
        return preg_match("/{.*}/", $value) ? true : false;
    }
}
