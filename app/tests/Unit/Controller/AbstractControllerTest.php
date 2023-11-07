<?php

use App\Core\Route\Route;
use App\Main\Controller\Start;
use PHPUnit\Framework\TestCase;

class AbstractControllerTest extends TestCase
{
    /** @test */
    public function TestItShouldReturnRouteIfSet()
    {
        $controller = new Start();
        $route = new Route('/example', null, null);
        $controller->setControllerRoute($route);

        $result = $controller->getRoute();

        $this->assertInstanceOf(Route::class, $result);
    }

    /** @test */
    public function TestItShouldSetVuexTemplateWithGivenFileAndAttributes()
    {
        $controller = new Start();
        $file = '../template/home.html';
        $attributes = [
            'loop' => [
                ["number" => 1],
                ["number" => 2]
            ],
            'zero' => true
        ];

        $controller->setTemplate($file, $attributes);
        $vuexTemplate = $controller->getTemplate();

        $this->assertIsString($vuexTemplate);
    }

    /** @test */
    public function TestItShouldThrowExceptionIfGetTemplateCalledWithoutSettingTemplate()
    {
        $controller = new Start();
        $success = false;

        try {
            $controller->getTemplate();
        } catch (Throwable) {
            $success = true;
        }

        $this->assertTrue($success);
    }
}
