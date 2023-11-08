<?php

use App\Core\Controller\VuexTemplate;
use PHPUnit\Framework\TestCase;

class VuexTemplateTest extends TestCase
{
    public function itShouldRenderTemplateWithVariables()
    {
        $template = new VuexTemplate(__DIR__ . '/Template/testTemplateValues.htm');
        $attributes = [
            'name' => 'John Doe',
            'age' => 30,
        ];

        $template->setVariables($attributes);
        $renderedTemplate = $template->render();

        $this->assertEquals('<p>Name: John Doe</p><p>Age: 30</p>', $renderedTemplate);
    }

    /** @test */
    public function itShouldHandleLoopContent()
    {
        $template = new VuexTemplate(__DIR__ . '/Template/testTemplateWithLoop.html');
        $attributes = [
            'loop' => [
                ['name' => 'John Doe'],
                ['name' => 'Jane Doe'],
            ],
        ];

        $template->setVariables($attributes);
        $renderedTemplate = $template->render();

        $this->assertEquals('<p>Name: John Doe</p><p>Name: Jane Doe</p>', $renderedTemplate);
    }

    /** @test */
    public function itShouldConditionallyIncludeContent()
    {
        $template = new VuexTemplate(__DIR__ . '/Template/testTemplateWithCondition.html');
        $attributes = [
            'include' => true,
            'exclude' => false,
        ];

        $template->setVariables($attributes);
        $renderedTemplate = $template->render();

        $this->assertEquals('<p>Include this content</p>', $renderedTemplate);
    }
}