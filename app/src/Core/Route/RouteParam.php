<?php

namespace App\Core\Route;

class RouteParam
{
    public function setParams(Route $route, array $params) : string
    {
        $urls = explode('/',$route->getUrl());
        foreach ($urls as $key => $url) {
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);

            if(count($matches)===3) {
                $urls[$key] = $params[$matches[2]];
            }

        }

        return implode("/", $urls);
    }

    public function getParams(array $urls,array $serverUrl) : array
    {
        $params = [];

        foreach ($urls as $key => $url) {
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);

            if(count($matches) === 3) {
                $params[$matches[2]] = $serverUrl[$key];
            }
        }

        return $params;
    }
}