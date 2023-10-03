<?php

namespace App\Core\Route;

use App\Core\Controller\AbstractController;

class Route
{
    private string $url;
    private ?AbstractController $controller;
    private ?string $name;
    private array $params;

    function __construct(string $url, ?AbstractController $controller,?string $name, $params = [])
    {
        $this->url = $url;
        $this->controller = $controller;
        $this->name = $name;
        $this->params = $params;
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


}