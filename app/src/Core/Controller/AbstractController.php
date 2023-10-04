<?php

namespace App\Core\Controller;

use App\Core\Route\Route;

abstract class AbstractController
{
    private ?Route $route;
    private VuexTemplete $vuexTemplete;

    protected function setTemplete(){
        $this->vuexTemplete = new VuexTemplete('../templete/home.html');
        $this->vuexTemplete->CAdd('[#test#]','fqeffewfewf');
        $this->vuexTemplete->CIf('zero',false);
        $this->vuexTemplete->CLoop('loop',[
            [
                "number" => 1
            ],
            [
                "number" => 2
            ]
        ]);
    }

    public function getTemplete(){
        return $this->vuexTemplete->CGet();
    }

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
