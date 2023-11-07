<?php

use App\Core\Route\Route;
use App\Main\Controller\Start;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    public function testGetName()
    {
        $route = new Route('/example', new Start(), 'example_route');

        $this->assertEquals('example_route', $route->getName());
    }

    /** @test */
    public function testGetController()
    {
        $route = new Route('/example', new Start(), 'example_route');

        $this->assertInstanceOf(Start::class, $route->getController());
    }

    /** @test */
    public function testGetParams()
    {
        $params = ['param1' => 'value1', 'param2' => 'value2'];
        $route = new Route('/example', new Start(), 'example_route', $params);

        $this->assertEquals($params, $route->getParams());
    }

    /** @test */
    public function testGetUrl()
    {
        $route = new Route('/example', new Start(), 'example_route');

        $this->assertEquals('/example', $route->getUrl());
    }

    /** @test */
    public function testIsHome()
    {
        $route = new Route('/example', new Start(), 'example_route', [], true);

        $this->assertTrue($route->isHome());
    }
}