<?php

namespace App\Core\Controller;

use Exception;
use App\Core\Route\Route;
use App\Core\Form\FormBulider;
use App\Core\Auth\Authenticate;
use App\Core\Form\AbstractForm;
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
    private FormBulider $formBulider;

    public function __construct(array $gards = [])
    {
        $connect = Connect::getInstance();
        $this->engine = $connect->getEngine();
        $this->auth = new Authenticate($this->engine);
        $this->formBulider = new FormBulider();

        foreach($gards as $gard){
            $gardObj = new $gard;
            $gardObj->authorized($this);
        }
    }

    public function setForm($form){

        if(!new $form() instanceof AbstractForm){
            throw new Exception('This not instance of AbstractForm !');
        }

        return $this->formBulider->setForm(new $form());
    }

    public function getForm() : ?array
    {
        if($this->formBulider->getAbstractForm() === null){
            return null;
        }

        return $this->formBulider->getForm();  
    }

    public function genrateForm(array $attribute){
        return $this->formBulider->genrate($attribute);
    }

    public function getModel($model){

        if(!new $model($this->engine) instanceof AbstractModel){
            throw new Exception('This not instance of AbstractModel !');
        }

        return new $model($this->engine);
    }

    public function getAuthenticate(){
        return $this->auth;
    }

    abstract public function InitMain() : void;

    public function main(){
        if( count($_POST) > 0 ){
            return $this->onPost($_POST);
        }

        return $this->InitMain();
    }

    public function onPost(array $POST): void {}

    public function createAuthFromModel($model,array $modification = []){

    }

    public function createFormModel($model,array $modification ,int $id = null){

        if(!new $model($this->engine) instanceof AbstractModel){
            throw new Exception('This not instance of AbstractModel !');
        }

        $this->formBulider->createFormModel(new $model($this->engine),$modification,$id);
    }

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
