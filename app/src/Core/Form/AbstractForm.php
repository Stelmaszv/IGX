<?php

namespace App\Core\Form;

abstract class AbstractForm
{
    public bool $submit = true;

    private array $fields = [];

    function __construct()
    {
        $this->initFields();
    }

    protected abstract function initFields() : void;

    protected function addField(FormGenerator $form){
        $this->fields[] = $form; 
    }

    public function getFields(){
        return $this->fields;
    }
}
