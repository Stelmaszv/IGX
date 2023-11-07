<?php
use App\Core\Controller\AbstractController;
use App\Core\Route\Route;
use App\Core\Route\RouteMatch;
use PHPUnit\Framework\TestCase;

class RouteMatchTest extends TestCase
{
    /** @test */
    public function testAddRoute()
    {
        $routeMatch = new RouteMatch();
        $controllerMock = $this->getMockBuilder(AbstractController::class)->getMock();

        $route = [
            'url' => '/example',
            'Controller' => $controllerMock,
            'name' => 'example_route',
        ];

        $routeMatch->addRoute($route);

        $ReflectionClass = new ReflectionClass($routeMatch);
        $routes = $ReflectionClass->getProperty('routes');
        $routes->setAccessible(true);
        $routesVar = $routes->getValue($routeMatch);

        $this->assertCount(1, $routesVar);
        $this->assertEquals('example_route', $routeMatch->getRoutes()[0]->getName());
    }

    /** @test */
    public function testSetRoute()
    {
        $routeMatch = new RouteMatch();
        $controllerMock = $this->getMockBuilder(AbstractController::class)->getMock();

        $route1 = [
            'url' => '/example1',
            'Controller' => $controllerMock,
            'name' => 'example_route1',
        ];

        $route2 = [
            'url' => '/example2',
            'Controller' => $controllerMock,
            'name' => 'example_route2',
        ];

        $routeMatch->addRoute($route1);
        $routeMatch->addRoute($route2);
        $routeMatch->setRoute();

        $ReflectionClass = new ReflectionClass($routeMatch);
        $routes = $ReflectionClass->getProperty('activeController');
        $routes->setAccessible(true);
        $activeControllerVar = $routes->getValue($routeMatch);

        $this->assertEquals('example_route1', $activeControllerVar);
    }

    /** @test */
    public function testGetRouteAsObject()
    {
        $routeMatch = new RouteMatch();
        $controllerMock = $this->getMockBuilder(AbstractController::class)
            ->getMock();

        $route = [
            'url' => '/example',
            'Controller' => $controllerMock,
            'name' => 'example_route',
        ];

        $routeMatch->addRoute($route);

        $objRoute = $routeMatch->getRouteAsObject('example_route', ['param' => 'value']);

        $this->assertInstanceOf(Route::class, $objRoute);
        $this->assertEquals(['param' => 'value'], $objRoute->getParams());

        $nonExistentRoute = $routeMatch->getRouteAsObject('nonexistent_route');

        $this->assertNull($nonExistentRoute);
    }
}