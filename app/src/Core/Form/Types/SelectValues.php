<?php

namespace App\Core\Form\Types;

use App\Core\Form\FormGenerator;

class SelectValues implements FormGenerator
{
    private array $attribute;

    function __construct(array $attribute = [])
    {
        $this->attribute = $attribute;
    }

    private function generateOptions() : string 
    {
        $options = '';

        foreach ($this->attribute['options'] as $option){
            $options .= '<option value="'.$option.'">'.$option.'</option>'; 
        }

        return $options;
    }

    public function generate() : string
    {
        if(isset($this->attribute['div']) || isset($this->attribute['divClass'])){
            $divClass = (isset($this->attribute['divClass'])) ? 'class='.$this->attribute['divClass'].'' : '';
            return '<div '.$divClass.'>'.$this->generateLabel().''.$this->generateSelect().'</div>';
        }

        return $this->generateLabel().''.$this->generateSelect();

    }

    private function generateLabel(): string
    {   
        return (isset($this->attribute['label']) &&  isset($this->attribute['id'])) ? '<label for="'.$this->attribute['id'].'">'.$this->attribute['label'].'</label>' : '';
    }

    public function generateSelect() : string
    {
        $type = (isset($this->attribute['type'])) ? 'type='.$this->attribute['type'].'' : '';
        $id = (isset($this->attribute['id'])) ? 'id='.$this->attribute['id'].'' : '';
        $class = (isset($this->attribute['class'])) ? 'class='.$this->attribute['class'].'' : '';
        $name = (isset($this->attribute['name'])) ? 'name='.$this->attribute['name'].'' : '';

        $select = '<select '.$id.' '.$class.' '.$type.' '.$name.'>';
        $select .= $this->generateOptions();
        $select .= '</select>';

        return $select;
    }
}
