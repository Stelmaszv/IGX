<?php

namespace App\Core\Route;

class RouteMatch
{
    static $routes = [];
    static $activeController = null;

    static function resolve(string $urlMain,$controller,$name){

        $urls = explode('/',$urlMain);
        $serverUrl = explode('/',$_SERVER['REQUEST_URI']);

        foreach ($urls as $url){
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);
            if(count($matches)===3) {
                self::validateUrl($matches);
            }
        }

        if(self::isMatched($urls,$serverUrl)){
            self::$activeController = $name;
            $controller->main();
        }
    }

    static public function getActiveRoute(){
        foreach (self::$routes as $routeEl){
            self::resolve(
                $routeEl['url'],
                $routeEl['Controller'],
                $routeEl['name']
            );
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

        return count($urlMatchArray) === count($serverUrls)-1;
    }

    static private function validateUrl(array $matches){
        if($matches[1] !== 'string' && $matches[1] !== 'int'){
            throw new RouteException("Invalid type in Route !");
        }
    }

    static public function checkIfRouteExist(){
        if(self::$activeController === null){
            throw new RouteException("Route not Exist !");
        }
    }

    static public function addRoute(array $route){

        foreach (self::$routes as $routeEl){
            if($route['name'] === $routeEl['name']){
                throw new RouteException("Route with this name already exist !");
            }
        }
        self::$routes[] = $route;
    }
}
