<?php

namespace App\Core\Model\Fields;

use App\Core\Model\AbstractModel;
use App\Core\Model\Field;
use App\Core\Model\ModelException;

class FieldINT implements Field
{
    private string $name;
    private ?int $length;
    private bool $isNull;
    private ?string $nevName = null;

    public function __construct(
        AbstractModel $abstractModel,
        string $name,
        ?int $length = null,
        bool $isNull = false
    ){

        if(!$abstractModel->migrationBuilder->checkIfColumnExist($name)){
            $this->nevName = $name;
        }

        if ($length > 255) {
            throw new ModelException("Int length is grater ten 255 ! ");
        }

        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
    }

    public function getNevName(): ?string
    {
        return $this->nevName;
    }

    public function isNull() : bool
    {
        return $this->isNull;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getLength() : ?int
    {
        return $this->length;
    }

    public function getFieldName() : string
    {
        if($this->getLength()){
            return "INT(".$this->getLength().")";
        }else{
            return "INT";
        }
    }
}