<?php

namespace App\Core\Route;

use App\Core\Controller\AbstractController;

class RouteMatch
{
    private array $routes = [];
    private ?string $activeController = null;
    private array $serverUrl;
    private RouteValidator $routeValidator;
    private RouteParam $routeParams;

    public function __construct()
    {
        $this->routeValidator = new RouteValidator();
        $this->routeParams = new RouteParam();
    }

    private function resolve(string $urlMain,AbstractController $controller, string $name, bool $home) : void
    {
        $urls = explode('/',$urlMain);
        $this->serverUrl = explode('/',$_SERVER['REQUEST_URI']);

        if($this->serverUrl === null) {
            $this->routeValidator->validateActiveRoute($urls, $this->serverUrl);
        }

        foreach ($urls as $url){
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, $url, $matches);
            if(count($matches) === 3) {
                $this->routeValidator->validateUrl($matches);
            }
        }

        if($this->isMatched($urls,$this->serverUrl) && $this->activeController === null){
            $this->activeController = $name;
            $controller->setControllerRoute(
                new Route(
                    $urlMain,
                    null,
                    $name,
                    $this->routeParams->getParams($urls,$this->serverUrl)
                )
            );
            $controller->main();
        }

        if($home && $this->activeController === null){
            $this->activeController = $name;
            $controller->setControllerRoute(
                new Route(
                    $urlMain,
                    null,
                    $name,
                    []
                )
            );
            $controller->main();
        }
    }

    private function isMatched(array $urls,array $serverUrls) : bool
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

        return (count($urlMatchArray) === count($serverUrls)-1);
    }

    public function addRoute(array $route) : void
    {
        $countHome = 0;
        foreach ($this->routes as $routeEl){
            if($route['name'] === $routeEl->getName()){
                throw new RouteException("Route with this name already exist !");
            }

            if(true === $routeEl->isHome() && $countHome < 1){
                throw new RouteException("Cant be more then one controller home !");
            }
            $countHome++;
        }

        $this->routes[] = new Route(
            $route['url'],
            $route['Controller'],
            $route['name'],
            [],
            (isset($route['home'])) ? $route['home'] : false
        );
    }

    public function setRoute() : void
    {
        foreach ($this->routes as $routeEl){
            $this->resolve(
                $routeEl->getUrl(),
                $routeEl->getController(),
                $routeEl->getName(),
                false
            );
        }

        if(null === $this->activeController){
            foreach ($this->routes as $routeEl){
                if($routeEl->isHome()) {
                    $this->resolve(
                        $routeEl->getUrl(),
                        $routeEl->getController(),
                        $routeEl->getName(),
                        true
                    );
                }
            }
        }

        $this->routeValidator->checkIfRouteExist($this->activeController);
    }

    public function getRouteAsObject(string $name,array $params = []) : ?Route
    {
        foreach ($this->routes as $routeEl){
            if( null !== $name && $name === $routeEl->getName() ){
                return new Route(
                    (count($params))? $this->routeParams->setParams($routeEl,$params) : $routeEl->getUrl(),
                    $routeEl->getController(),
                    $routeEl->getName(),
                    $params
                );
            }
        }

        return null;
    }
}
