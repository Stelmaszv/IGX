<?php

namespace App\Core\Form;

use Exception;
use App\Core\Model\Field;
use App\Core\Form\Types\Input;
use App\Core\Form\Types\Button;
use App\Core\Form\Types\Textarea;
use App\Core\Model\AbstractModel;
use App\Core\Form\Types\SelectValues;

class GetFromFromModel
{
    private ?int $id;
    private AbstractModel $model;
    private array $modification;

    public function __construct(AbstractModel $model, array $modification, int $id = null)
    {
        $this->model = $model;
        $this->modification = $modification;
        $this->id = $id;
    }

    public function createForm(): TempleteForm
    {
        $templateForm = new TempleteForm();

        foreach ($this->model->getFields() as $field) {
            $objModel = $field;
            $fieldClassName = FromFields::FIELDS[basename(str_replace('\\', '/', get_class($field)))];
            $exclude = isset($this->modification['exclude']) ? !in_array($objModel->getName(), $this->modification['exclude']) : true;

            $labelSeparator = isset($this->modification['labelSeparator']) ? ' ' . $this->modification['labelSeparator'] . ' ' : '';

            $fields = [
                'type' => $this->getFieldModification($objModel, 'type', $fieldClassName),
                'name' => $this->getFieldModification($objModel, 'name', $objModel->getName()),
                'label' => $this->getFieldModification($objModel, 'label', ucfirst($objModel->getName()) . $labelSeparator),
                'class' => $this->getFieldModification($objModel, 'class', $fieldClassName)
            ];

            $fields['id'] = $this->getFieldModification($objModel, 'id', ucfirst($objModel->getName()));

            if ($exclude) {
                if (isset($this->modification['div'])) {
                    $fields['divClass'] = $this->modification['div'];
                }

                if ($this->id) {
                    $method = 'get' . $objModel->getName();
                    $fields['value'] = $this->model->get($this->id)->$method();
                }

                $this->addFieldBasedOnFieldType($fieldClassName, $templateForm, $fields, $objModel);
            }
        }

        if (isset($this->modification['submit'])) {
            $templateForm->addField(new Button($this->modification['submit']));
        }

        return $templateForm;
    }

    private function getFieldModification(Field $objModel, string $attribute, string  $default): string
    {
        $fieldName = $objModel->getName();
        return isset($this->modification['fields'][$fieldName][$attribute]) ? $this->modification['fields'][$fieldName][$attribute] : $default;
    }

    private function addFieldBasedOnFieldType(string $fieldClassName, TempleteForm $templateForm,array $fields, Field $objModel)
    {
        switch ($fieldClassName) {
            case 'text':
            case 'number':
                $templateForm->addField(new Input($fields));
                break;
            case 'textarea':
                $templateForm->addField(new Textarea($fields));
                break;
            case 'selectValue':
                $fields['options'] = $objModel->getOptions();
                $templateForm->addField(new SelectValues($fields));
                break;
            default:
                throw new Exception('Invalid Field!');
        }
    }
}
