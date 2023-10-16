<?php

use App\Core\Route\Route;
use App\Core\Route\RouteException;
use App\Core\Route\RouteMatch;
use App\Main\Controller\Start;
use PHPUnit\Framework\TestCase;

class RouteMatchTest extends TestCase
{
    /** @test */
    Public Function addRouteSuccess(){
        $routeMatch = new RouteMatch();
        $routeMatch->addRoute(
            [
                'url' => '/catse/{string:category}/{int:id}',
                'Controller' => new Start(),
                'name' => 'Route'
            ]
        );

        $ReflectionClass = new ReflectionClass($routeMatch);
        $routes = $ReflectionClass->getProperty('routes');
        $routes->setAccessible(true);
        $routesVar = $routes->getValue($routeMatch);

        $this->assertEquals(count($routesVar), 1);

    }

    /** @test */
    Public Function addRouteFailure(){
        $routeMatch = new RouteMatch();
        $failure = false;

        try {
            $routeMatch->addRoute(
                [
                    'url' => '/catse/{string:category}/{int:id}',
                    'Controller' => new Start(),
                    'name' => 'Route1'
                ]
            );
            $routeMatch->addRoute(
                [
                    'url' => '/catse/{string:category}/{int:id}',
                    'Controller' => new Start(),
                    'name' => 'Route1'
                ]
            );
        } catch (RouteException) {
            $failure = true;
        }

        $this->assertEquals($failure, true);
    }

    /** @test */
    Public Function getRouteAsObjectSuccess(){
        $routeMatch = new RouteMatch();

        $routeMatch->addRoute(
            [
                'url' => '/catese/{string:category}/{int:id}',
                'Controller' => new Start(),
                'name' => 'Route',
            ]
        );

        $routeMatch->addRoute(
            [
                'url' => '/',
                'Controller' => new Start(),
                'name' => 'home',
                'home' => true
            ]
        );

        $routeMatch->setRoute();

        $routeMatch->getRouteAsObject('home');

        $this->assertEquals($routeMatch->getRouteAsObject('home') instanceof Route, true);

    }



}