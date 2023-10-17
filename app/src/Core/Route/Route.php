<?php

namespace App\Core\Route;

use App\Core\Controller\AbstractController;

class Route
{
    private string $url;
    private ?AbstractController $controller;
    private ?string $name;
    private array $params;
    private bool $home;

    function __construct(string $url, ?AbstractController $controller,?string $name, array $params = [],bool $home = false)
    {
        $this->url = $url;
        $this->controller = $controller;
        $this->name = $name;
        $this->params = $params;
        $this->home = $home;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getController(): ?AbstractController
    {
        return $this->controller;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isHome(): bool
    {
        return $this->home;
    }


}