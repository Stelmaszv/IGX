<?php

namespace App\Core\Form\Types;

use App\Core\Form\FormGenerator;

class Input implements FormGenerator
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

        return $divWrapper . $this->generateLabel() . $this->generateInput() . $divWrapperClose;
    }

    private function generateLabel(): string
    {
        return (isset($this->attribute['label']) && isset($this->attribute['id'])) ? '<label for="' . $this->attribute['id'] . '">' . $this->attribute['label'] . '</label>' : '';
    }

    private function generateInput(): string
    {
        $input = '<input ';

        foreach ($this->attribute as $key => $attribut) {
            if ($key != 'label' && $key != 'divClass' && $key != 'div') {
                $input .= $key . '=' . $attribut . ' ';
            }
        }

        return $input . '>';
    }
}

