<?php

namespace App\Core\Route;

use App\Core\Controller\AbstractController;

class RouteMatch
{
    static private $routes = [];
    static private $activeController = null;
    static private $serverUrl = null;

    static private function resolve(string $urlMain,AbstractController $controller,string $name, ?string $value = null) : void
    {
        $urls = explode('/',$urlMain);

        self::$serverUrl = ($value !== null) ? explode('/',$value) : explode('/',$_SERVER['REQUEST_URI']);

        if(self::$serverUrl === null) {
            self::validateActiveRoute($urls, self::$serverUrl);
        }

        foreach ($urls as $url){
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);
            if(count($matches) === 3) {
                self::validateUrl($matches);
            }
        }

        if(self::isMatched($urls,self::$serverUrl,$name) && self::$activeController === null){
            self::$activeController = $name;
            $controller->setControllerRoute(
                new Route(
                    $urlMain,
                    null,
                    $name,
                    self::getParamsFormUrl($urls)
                )
            );
            $controller->main();
        }
    }

    static public function getRouteAsObject(string $name,array $params = []) : ?Route
    {
        foreach (self::$routes as $routeEl){
            if( null !== $name && $name === $routeEl->getName() ){
                return new Route(
                    (count($params))? self::setParamsForActiveRoute($routeEl,$params) : $routeEl->getUrl(),
                    $routeEl->getController(),
                    $routeEl->getName(),
                    $params
                );
            }
        }

        return null;
    }

    static private function setParamsForActiveRoute(Route $route, array $params) : string
    {
        $urls = explode('/',$route->getUrl());
        foreach ($urls as $key => $url) {
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);

            if(count($matches)===3) {
                $urls[$key]=$params[$matches[2]];
            }

        }

        return self::$serverUrl = implode("/", $urls);
    }

    static private function getParamsFormUrl($urls) : array
    {
        $params = [];

        foreach ($urls as $key => $url) {
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);

            if(count($matches) === 3) {
                $params[$matches[2]] = self::$serverUrl[$key];
            }
        }

        return $params;
    }

    static public function setHomeRouteIfNotActiveRoute() : void
    {
        self::setActiveRoute('home');
    }

    static public function setActiveRoute(?string $name = null,array $params = []) : void
    {
        foreach (self::$routes as $routeEl){
            if( null !== $name && $name === $routeEl->getName()){
                if (count($params)){
                    self::setParamsForActiveRoute($routeEl,$params);
                }
                self::resolve(
                    $routeEl->getUrl(),
                    $routeEl->getController(),
                    $routeEl->getName(),
                );
            }
            self::resolve(
                $routeEl->getUrl(),
                $routeEl->getController(),
                $routeEl->getName(),
            );

        }
    }

    static private function isMatched(array $urls,array $serverUrls, string $name) : bool
    {
        $urlMatchArray = [];

        if(count($serverUrls) === count($urls)){
            foreach ($serverUrls as $serverKey => $serverUrl) {

                if(!empty($serverUrls[$serverKey]) && $serverUrls[$serverKey] === $urls[$serverKey] && !empty($urls[$serverKey])){
                    $urlMatchArray[] = $serverKey;
                }

                $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
                preg_match($pattern, $urls[$serverKey], $matches);

                if(count($matches)===3) {
                    if ($matches[1] === 'int' && intval($serverUrls[$serverKey]) != 0) {
                        $urlMatchArray[] = 'int';
                    }

                    if ($matches[1] === 'string' && $serverUrls[$serverKey] != 0) {
                        $urlMatchArray[] = 'string';
                    }

                }
            }
        }

        if($name === 'home'){
            return true;
        }

        return (count($urlMatchArray) === count($serverUrls)-1);
    }

    static private function validateUrl(array $matches) : void
    {
        if($matches[1] !== 'string' && $matches[1] !== 'int'){
            throw new RouteException("Invalid type in Route !");
        }
    }

    static private function validateActiveRoute(array $urls,array $serverUrls) : void
    {
        if(count($urls) !== count($serverUrls)){
            throw new RouteException("Invalid data for Route !");
        }
    }

    static public function checkIfRouteExist() : void
    {
        if(self::$activeController === null){
            throw new RouteException("Route not Exist !");
        }
    }

    static public function addRoute(array $route) : void
    {
        foreach (self::$routes as $routeEl){
            if($route['name'] === $routeEl->getName()){
                throw new RouteException("Route with this name already exist !");
            }
        }
        self::$routes[] = new Route(
            $route['url'],
            $route['Controller'],
            $route['name'],
            []
        );
    }
}
