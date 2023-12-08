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
        $formTag = '<form ';
        foreach($attribute as $key => $attribut)
        {
            $formTag.= $key.'='.$attribut.' ';
        }

        $formTag.= '>';

        $form = $formTag;

        foreach ($this->formArray as $field){
            $form.= $field;
        }

        $form .= '</form>';

        return $form;
    }
}
