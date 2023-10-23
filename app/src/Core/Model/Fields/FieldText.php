<?php

namespace App\Core\Model\Fields;

use App\Core\Model\Field;

class FieldText implements Field
{
    private string $name;
    private ?int $length;
    private bool $isNull;

    public function __construct(
        string $name,
        ?int $length = null,
        bool $isNull = false
    ){
        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
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
            return "TEXT(".$this->getLength().")";
        }else{
            return "TEXT";
        }
    }
}