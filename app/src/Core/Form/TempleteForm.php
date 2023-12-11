<?php

namespace App\Core\Form;

class TempleteForm
{
    public bool $submit = true;
    private array $fields = [];

    public function addField(FormGenerator $form){
        $this->fields[] = $form; 
    }

    public function getFields(){
        return $this->fields;
    }
}
