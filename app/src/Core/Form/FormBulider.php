<?php

namespace App\Core\Form;

use Exception;
use App\Core\Model\AbstractModel;

class FormBulider
{
    private $form = null; 
    private array $formArray = []; 

    public function setForm($form){
        if($form instanceof AbstractForm || $form instanceof TempleteForm){
            $this->form = $form;
        }else{
            throw new Exception('This not instance of AbstractForm !');
        }
    }

    public function createFormModel(AbstractModel $model, array $modification, int $id = null){
       $model = new GetFromFromModel($model, $modification,$id);
       $this->setForm($model->createForm());
    }

    public function getForm(): ?array
    {
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

    public function getAbstractForm()
    {
        return $this->form;
    }
}
