<?php

namespace App\Core\Controller;

use Random\Engine;
use App\Core\Route\Route;
use App\Core\Auth\Authenticate;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

abstract class AbstractController
{
    private ?Route $route;
    private ?VuexTemplate $vuexTemplate;
    private Authenticate $authenticate;
    public DBInterface $engine;
    protected ?string $role = null;

    public function __construct()
    {
        $connect = Connect::getInstance();
        $this->engine = $connect->getEngine();
    }

    abstract public function main() : void;

    public function chceckAccess() : void{
        if($this->role !== null){
            $this->authenticate = new Authenticate($this->engine);

            if(!$this->authenticate->inLogin()){
                throw new UnauthorizedException('Unauthorized access !');
            }

            if(!$this->authenticate->getUser()->hasRole($this->role)){
                throw new UnauthorizedException('Unauthorized access !');
            }
        }
    }

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
