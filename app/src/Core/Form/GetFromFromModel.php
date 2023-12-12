<?php

namespace App\Core\Form;

use App\Core\Model\AbstractModel;

class GetFromFromModel
{
    private ?int $id;
    private AbstractModel $model;
    private $modelData;
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
            if($field !== 'texarea' && !in_array($objModel->getName(),$this->modification['exclude'])){
                $fields = [
                    'type' => isset($this->modification["fields"][$objModel->getName()]["type"])? $this->modification["fields"][$objModel->getName()]["type"] : $field,
                    'name' => isset($this->modification["fields"][$objModel->getName()]["name"])? $this->modification["fields"][$objModel->getName()]["name"] : $field, 
                    'id' => isset($this->modification["fields"][$objModel->getName()]["id"])? $this->modification["fields"][$objModel->getName()]["id"] : $field,
                    'label' => isset($this->modification["fields"][$objModel->getName()]["label"])? $this->modification["fields"][$objModel->getName()]["label"] : ucfirst($objModel->getName()),
                    'class' => isset($this->modification["fields"][$objModel->getName()]["class"])? $this->modification["fields"][$objModel->getName()]["class"] : $field,
                ];

                if($this->modification['div']){
                    $fields['divClass'] = $this->modification['div'];
                }

                if($this->id){
                    $method = 'get'.$objModel->getName();
                    $fields['value'] = $this->model->get($this->id)->$method();

                }

                $templeteForm->addField(new Input($fields));
            }
        }

        $templeteForm->addField(new Button($this->modification['submit']));

        return $templeteForm;
    }

}
