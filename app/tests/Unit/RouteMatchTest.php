<?php

use App\Core\Route\RouteException;
use App\Core\Route\RouteMatch;
use App\Main\Controller\Start;
use PHPUnit\Framework\TestCase;

class RouteMatchTest extends TestCase
{
    private $route;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->route = [
            'url' => '/cats/{string:category}/{int:id}',
            'Controller' => null,
            'name' => 'route1'
        ];
    }

    /** @test */
    public function addRouteSuccess()
    {
        RouteMatch::addRoute($this->route);
        $reflectionClass = new ReflectionClass('App\Core\Route\RouteMatch');

        $this->assertEquals(count([get_object_vars($reflectionClass->getProperty('routes'))]), 1);
    }

    /** @test */
    public function addRouteFailure()
    {
        $failure = false;
        try {
            RouteMatch::addRoute($this->route);
        } catch (RouteException) {
            $failure = true;
        }

        $this->assertEquals($failure, true);
    }

    /** @test */
    public function resolveSuccess()
    {
        $reflectionClass = new ReflectionClass('App\Core\Route\RouteMatch');
        $method = $reflectionClass->getMethod('resolve');
        $method->invoke($reflectionClass, '/cats/{string:category}/{int:id}', new Start(), 'route1', '/cats/cats/1');
        $activeController = $reflectionClass->getProperty('activeController');

        $this->assertEquals($activeController->getValue(), 'route1');
    }

    /** @test */
    public function validateUrlFailure()
    {
        $failure = false;
        try {
            $reflectionClass = new ReflectionClass('App\Core\Route\RouteMatch');
            $method = $reflectionClass->getMethod('validateUrl');
            $pattern = '/\{([a-zA-Z]+):([a-zA-Z]+)\}/';
            preg_match($pattern, '/cats/{str:category}/{int:id}', $matches);
            $method->invoke($reflectionClass, $matches);
        }catch (RouteException){
            $failure = true;
        }

        $this->assertEquals($failure, true);
    }

    /** @test */
    public function isMatchedSuccess()
    {
        $reflectionClass = new ReflectionClass('App\Core\Route\RouteMatch');
        $method = $reflectionClass->getMethod('isMatched');
        $stan = $method->invoke($reflectionClass, explode('/','/cats/{string:category}/{int:id}'),  explode('/','/cats/cats/1',),'route1');

        $this->assertEquals($stan, true);
    }

}