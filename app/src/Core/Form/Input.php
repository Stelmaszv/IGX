<?php

namespace App\Core\Form;

class Input implements FormGenerator
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
            return '<div '.$divClass.'>'.$this->getLabel().''.$this->getInput().'</div>';
        }

        return $this->getLabel().''.$this->getInput();

    }

    private function getLabel(): string
    {
        return (isset($this->attribute['label']) &&  isset($this->attribute['id'])) ? '<label for="'.$this->attribute['id'].'">'.$this->attribute['label'].'</label>' : '';
    }

    private function getInput() : string 
    {
        $type = (isset($this->attribute['type'])) ? 'type='.$this->attribute['type'].'' : '';
        $id = (isset($this->attribute['id'])) ? 'id='.$this->attribute['id'].'' : '';
        $name = (isset($this->attribute['name'])) ? 'name='.$this->attribute['name'].'' : '';
        $class = (isset($this->attribute['class'])) ? 'class='.$this->attribute['class'].'' : '';

        return '<input '.$type.' '.$class.' '.$id.' '.$name.'>';
    }
}
