<?php

use App\Core\Route\RouteException;
use PHPUnit\Framework\TestCase;
use App\Core\Route\RouteValidator;

class RouteValidatorTest extends TestCase
{
    /** @test */
    public function validateUrlFailure()
    {
        $failure = false;
        try {
            $routeValidator = new RouteValidator();
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, '/cats/{str:category}/{int:id}', $matches);
            $routeValidator->validateUrl($matches);
        }catch (RouteException){
            $failure = true;
        }

        $this->assertEquals($failure, true);
    }

    /** @test */
    public function validateUrlSuccess()
    {
        $success = true;
        $routeValidator = new RouteValidator();
        $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
        preg_match($pattern, '/catese/{string:category}/{int:id}', $matches);
        try {
        $routeValidator->validateUrl($matches);
        }catch (RouteException){
            $success = false;
        }

        $this->assertEquals($success, true);
    }

    public function validateActiveRouteSuccess(){
        $success = true;
        $routeValidator = new RouteValidator();
        try {
        $routeValidator->validateActiveRoute(explode('/','/catese/{string:category}/{int:id}'),explode('/','/catese/cat/5'));
        }catch (RouteException){
            $success = false;
        }

        $this->assertEquals($success, true);
    }

    public function validateActiveRouteFailure(){
        $failure = false;
        $routeValidator = new RouteValidator();
        try {
            $routeValidator->validateActiveRoute(explode('/','/catese/{string:category}/{int:id}'),explode('/','/catese/fewf/cat/5'));
        }catch (RouteException){
            $failure = true;
        }

        $this->assertEquals($failure, true);
    }
}