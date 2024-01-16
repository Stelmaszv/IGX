<?php

namespace App\Core\Form\Types;

use App\Core\Form\FormGenerator;

class Textarea implements FormGenerator
{
    private array $attribute;

    function __construct(array $attribute = [])
    {
        $this->attribute = $attribute;
    }

    public function generate() : string
    {
        if(isset($this->attribute['div']) || isset($this->attribute['divClass'])){
            $divClass = (isset($this->attribute['divClass'])) ? 'class='.$this->attribute['divClass'].'' : '';
            return '<div '.$divClass.'>'.$this->getLabel().''.$this->getTextarea().'</div>';
        }

        return $this->getLabel().''.$this->getTextarea();

    }

    private function getLabel(): string
    {
        return (isset($this->attribute['label']) &&  isset($this->attribute['id'])) ? '<label for="'.$this->attribute['id'].'">'.$this->attribute['label'].'</label>' : '';
    }

    private function getAttributs() : string 
    {
        $input = '';

        foreach($this->attribute as $key => $attribut)
        {
            $input.= $key.'='.$attribut.' ';
        }

        return $input;
    }

    private function getTextarea() : string 
    {
        $value = isset($this->attribute['value']) ? $this->attribute['value'] : '';
        return 
        '<textarea 
            '.$this->getAttributs().'
            >'.$value.'
        </textarea>';
    }
}
