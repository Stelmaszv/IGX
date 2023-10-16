<?php

namespace App\Core\Controller;

use App\Core\Route\Route;

abstract class AbstractController
{
    private ?Route $route;
    private VuexTemplete $vuexTemplete;

    abstract public function main() : void;

    protected function setTemplete(string $file ,array $attributes = []){
        $this->vuexTemplete = new VuexTemplete($file);
        $this->vuexTemplete->getVarables($attributes);
    }

    public function getTemplete() : string
    {
        return $this->vuexTemplete->CGet();
    }

    public function setControllerRoute(Route $route) : void
    {
        $this->route = $route;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }
}
