<?php

namespace App\Core\Form;

class FormBulider
{
    private AbstractForm $form; 
    private array $formArray; 

    public function setForm(AbstractForm $form){
        $this->form = $form;
    }

    public function getForm(){
        $this->formArray = [];
        foreach ($this->form->getFields() as $field){
            $this->formArray[] = $field->generate();
        }

        return  $this->formArray;
    }

    public function genrate(array $attribute) : string
    {

        $method = (isset($attribute['method'])) ? 'method='.$attribute['method'].'' : '';
        $class = (isset($attribute['class'])) ? 'class='.$attribute['class'].'' : '';
        $id = (isset($attribute['id'])) ? 'id='.$attribute['id'].'' : '';
        $action = (isset($attribute['action'])) ? 'id='.$attribute['action'].'' : '';

        $form = '<form '.$method.' '.$class.' '.$id.' '.$action.'>';

        foreach ($this->formArray as $field){
            $form.= $field;
        }

        $form .= '</form>';

        return $form;
    }
}
