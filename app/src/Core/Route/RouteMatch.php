<?php

namespace App\Core\Route;

class RouteMatch
{
    static $routes = [];
    static $activeController = null;
    static $serverUrl = null;

    static function resolve(string $urlMain,$controller,$name){

        $urls = explode('/',$urlMain);
        self::$serverUrl = explode('/',$_SERVER['REQUEST_URI']);
        if(self::$serverUrl === null) {
            self::validateActiveRoute($urls, self::$serverUrl);
        }

        foreach ($urls as $url){
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);
            if(count($matches)===3) {
                self::validateUrl($matches);
            }
        }

        if(self::isMatched($urls,self::$serverUrl)){
            self::$activeController = $name;
            $controller->main();
        }
    }

    static private function setParamsForActiveRoute(array $route, array $params){


        $urls = explode('/',$route['url']);
        foreach ($urls as $key => $url) {
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);

            if(count($matches)===3) {
                $urls[$key]=$params[$matches[2]];
            }

        }

        self::$serverUrl = implode("/", $urls);
    }

    static public function setActiveRoute(?string $name = null,$params = []){
        foreach (self::$routes as $routeEl){
            if( null !== $name && $name === $routeEl['name'] ){
                if (count($params)){
                    self::setParamsForActiveRoute($routeEl,$params);
                }
                self::resolve(
                    $routeEl['url'],
                    $routeEl['Controller'],
                    $routeEl['name']
                );
            }
        }

        self::checkIfRouteExist();
    }


    static private function isMatched(array $urls,array $serverUrls){
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

        return (count($urlMatchArray) === count($serverUrls)) || count($urlMatchArray) === 0;
    }

    static private function validateUrl(array $matches):void
    {
        if($matches[1] !== 'string' && $matches[1] !== 'int'){
            throw new RouteException("Invalid type in Route !");
        }
    }

    static private function validateActiveRoute(array $urls,array $serverUrls):void
    {
        if(count($urls) !== count($serverUrls)){
            throw new RouteException("Invalid data for Route !");
        }
    }

    static public function checkIfRouteExist():void
    {
        if(self::$activeController === null){
            throw new RouteException("Route not Exist !");
        }
    }

    static public function addRoute(array $route):void
    {
        foreach (self::$routes as $routeEl){
            if($route['name'] === $routeEl['name']){
                throw new RouteException("Route with this name already exist !");
            }
        }
        self::$routes[] = $route;
    }
}
