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
        $input = '<input ';

        foreach($this->attribute as $key => $attribut)
        {
            if($key != 'label' && $key != 'divClass'){
                $input.= $key.'='.$attribut.' ';
            }
        }

        return $input.='>';
    }
}
