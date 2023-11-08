<?php

use App\Core\Route\Route;
use App\Core\Route\RouteParam;
use PHPUnit\Framework\TestCase;

class RouteParamTest extends TestCase
{
    /** @test */
    public function testSetParams()
    {
        $routeParam = new RouteParam();
        $route = new Route('/example_route/{string:exampleCategory}/{int:id}', null, null);
        $params = [
            "exampleCategory" => 'cars',
            "id" => 1
        ];

        $modifiedUrl = $routeParam->setParams($route, $params);

        $this->assertEquals('/example_route/cars/1', $modifiedUrl);
    }

    /** @test */
    public function testGetParams()
    {
        $routeParam = new RouteParam();
        $urls = '/example_route/{string:exampleCategory}/{int:id}';
        $serverUrl = '/example_route/cars/1';

        $params = $routeParam->getParams(explode('/',$urls), explode('/',$serverUrl));

        $this->assertEquals([
            "exampleCategory" => 'cars',
            "id" => 1
        ], $params);
    }
}