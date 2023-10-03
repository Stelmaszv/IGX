<?php

namespace App\Core\Controller;

use App\Core\Route\Route;

abstract class AbstractController
{
    private ?Route $route;

    abstract public function main() : void;
    public function setControllerRoute(Route $route) : void
    {
        $this->route = $route;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }
}
