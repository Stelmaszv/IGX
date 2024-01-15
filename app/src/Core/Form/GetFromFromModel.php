<?php

namespace App\Core\Form;

use App\Core\Form\Types\Input;
use App\Core\Form\Types\Button;
use App\Core\Model\AbstractModel;

class GetFromFromModel
{
    private ?int $id;
    private AbstractModel $model;
    private array $modification;

    function __construct(AbstractModel $model, array $modification, int $id = null)
    { 
        $this->model = $model;
        $this->modification = $modification;
        $this->id = $id;
    }

    public function createForm() : TempleteForm
    {
        $templeteForm = new TempleteForm();

        foreach($this->model->getFields() as $field){
            $objModel = $field;
            $field = explode('\\',get_class($field));
            $field = FromFields::FIELDS[end($field)];
            $exclude = isset($this->modification['exclude']) ? !in_array($objModel->getName(),$this->modification['exclude']) : true;
            if($field !== 'texarea' && $exclude){
                $labelSeperetor = isset($this->modification['labelSeperetor'])? ' '.$this->modification['labelSeperetor'].' ' : '';

                $fields = [
                    'type' => isset($this->modification["fields"][$objModel->getName()]["type"])? $this->modification["fields"][$objModel->getName()]["type"] : $field,
                    'name' => isset($this->modification["fields"][$objModel->getName()]["name"])? $this->modification["fields"][$objModel->getName()]["name"] : $objModel->getName(), 
                    'label' => isset($this->modification["fields"][$objModel->getName()]["label"])? $this->modification["fields"][$objModel->getName()]["label"].$labelSeperetor : ucfirst($objModel->getName()).$labelSeperetor,
                    'class' => isset($this->modification["fields"][$objModel->getName()]["class"])? $this->modification["fields"][$objModel->getName()]["class"] : $field
                ];

                $fields['id'] = $fields['label'];

                if(isset($this->modification['div'])){
                    $fields['divClass'] = $this->modification['div'];
                }

                if($this->id){
                    $method = 'get'.$objModel->getName();
                    $fields['value'] = $this->model->get($this->id)->$method();

                }

                $templeteForm->addField(new Input($fields));
            }
        }

        if(isset($this->modification['submit'])){
            $templeteForm->addField(new Button($this->modification['submit']));
        }
        
        return $templeteForm;
    }

}
