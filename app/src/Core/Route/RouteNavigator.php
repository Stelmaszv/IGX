<?php

namespace App\Core\Route;

use App\Core\Route\RouteParam;

class RouteNavigator
{
    private array $routes = [];
    private RouteParam $routeParams;

    function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->routeParams = new RouteParam();
    }
    public function getRouteAsObject(string $name, array $params = []) : ?Route
    {
        foreach ($this->routes as $routeEl) {
            if (null !== $name && $name === $routeEl->getName()) {
                return new Route(
                    (count($params)) ? $this->routeParams->setParams($routeEl, $params) : $routeEl->getUrl(),
                    $routeEl->getController(),
                    $routeEl->getName(),
                    $params
                );
            }
        }

        return null;
    }
}
