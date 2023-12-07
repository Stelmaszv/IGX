<?php

namespace App\Core\Controller;

use Random\Engine;
use App\Core\Route\Route;
use App\Core\Route\RouteParam;
use App\Core\Auth\Authenticate;
use App\Core\Model\AbstractModel;
use App\Core\Route\RouteNavigator;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractController
{
    private ?Route $route;
    private ?VuexTemplate $vuexTemplate;
    private Authenticate $auth;
    private DBInterface $engine;
    protected RouteNavigator $routeNavigator;
    protected ?string $role = null;

    public function __construct(array $gards = [])
    {
        $connect = Connect::getInstance();
        $this->engine = $connect->getEngine();
        $this->auth = new Authenticate($this->engine);

        foreach($gards as $gard){
            $gardObj = new $gard;
            $gardObj->authorized($this);
        }
    }

    public function getModel($model){
        return new $model($this->engine);
    }

    public function getAuthenticate(){
        return $this->auth;
    }

    abstract public function main() : void;

    public function chceckAccess() : void{
        if($this->role !== null){

            if(!$this->auth->inLogin()){
                throw new UnauthorizedException('Unauthorized access !');
            }

            if($this->role !== 'login' && !$this->auth->getUser()->hasRole($this->role)){
                throw new UnauthorizedException('Unauthorized access !');
            }

        }
    }

    public function setRoutes(array $routes){
        $this->routeNavigator = new RouteNavigator($routes);
    }

    protected function setTemplate(string $file ,array $attributes = []) : void
    {
        $this->vuexTemplate = new VuexTemplate($file);
        $this->vuexTemplate->setVariables($attributes);
    }

    protected function getTemplate() : ?string
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
