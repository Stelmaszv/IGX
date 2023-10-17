<?php

use App\Core\Route\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /** @test */
    public function RouteSuccess() {
        $route = new Route(
          'url',
          null,
            'name',
            [],
            false
        );

        $this->assertEquals($route->getUrl(),'url');
        $this->assertEquals($route->getController(),null);
        $this->assertEquals($route->getName(),'name');
        $this->assertEquals($route->getParams(),[]);
        $this->assertEquals($route->isHome(),false);

    }

}