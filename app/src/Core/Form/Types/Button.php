<?php

namespace App\Core\Form\Types;

use App\Core\Form\FormGenerator;

class Button  implements FormGenerator
{
    private array $attribute;

    function __construct(array $attribute = [])
    {
        $this->attribute = $attribute;
    }

    public function generate() : string
    {
        $type = (isset($this->attribute['type'])) ? 'type='.$this->attribute['type'].'' : '';
        $id = (isset($this->attribute['id'])) ? 'id='.$this->attribute['id'].'' : '';
        $label = (isset($this->attribute['label'])) ? $this->attribute['label'] : '';
        $class = (isset($this->attribute['class'])) ? 'class='.$this->attribute['class'].'' : '';

        return '<button '.$id.' '.$class.' '.$type.'>'.$label.'</button>';

    }
}
