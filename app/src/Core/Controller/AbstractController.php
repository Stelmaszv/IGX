<?php

namespace App\Core\Controller;

use App\Core\Route\Route;

abstract class AbstractController
{
    private ?Route $route;
    private ?VuexTemplate $vuexTemplate;

    abstract public function main() : void;

    public function setTemplate(string $file ,array $attributes = []) : void
    {
        $this->vuexTemplate = new VuexTemplate($file);
        $this->vuexTemplate->setVariables($attributes);
    }

    public function getTemplate() : ?string
    {
        return $this->vuexTemplate?->render();
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
