<?php

namespace App\Core\Route;

class RouteValidator
{
    public function validateUrl(array $matches) : void
    {
        $validTypes = ['string', 'int'];

        if (!in_array($matches[1], $validTypes)) {
            throw new RouteException("Invalid type in Route !");
        }
    }

    public function validateActiveRoute(array $urls, array $serverUrls) : void
    {
        if (count($urls) !== count($serverUrls)) {
            throw new RouteException("Invalid data for Route !");
        }
    }

    public function checkIfRouteExist(?string $activeController) : void
    {
        if ($activeController === null) {
            throw new RouteException("Route not Exist !");
        }
    }
}