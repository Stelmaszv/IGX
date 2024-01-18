<?php

namespace App\Core\Form\Types;

use App\Core\Form\FormGenerator;

class Textarea implements FormGenerator
{
    private array $attribute;

    public function __construct(array $attribute = [])
    {
        $this->attribute = $attribute;
    }

    public function generate(): string
    {
        $divClass = (isset($this->attribute['divClass'])) ? 'class=' . $this->attribute['divClass'] : '';
        $divWrapper = (isset($this->attribute['div']) || isset($this->attribute['divClass'])) ? '<div ' . $divClass . '>' : '';
        $divWrapperClose = (isset($this->attribute['div']) || isset($this->attribute['divClass'])) ? '</div>' : '';

        return $divWrapper . $this->generateLabel() . $this->generateTextarea() . $divWrapperClose;
    }

    private function generateLabel(): string
    {
        return (isset($this->attribute['label']) && isset($this->attribute['id'])) ? '<label for="' . $this->attribute['id'] . '">' . $this->attribute['label'] . '</label>' : '';
    }

    private function generateAttributes(): string
    {
        $attributes = '';

        foreach ($this->attribute as $key => $value) {
            if ($key !== 'label' && $key !== 'divClass' && $key !== 'div') {
                $attributes .= $key . '="' . $value . '" ';
            }
        }

        return $attributes;
    }

    private function generateTextarea(): string
    {
        $value = isset($this->attribute['value']) ? $this->attribute['value'] : '';
        return '<textarea ' . $this->generateAttributes() . '>' . $value . '</textarea>';
    }
}
