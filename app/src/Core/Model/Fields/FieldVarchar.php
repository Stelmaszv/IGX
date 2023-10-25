<?php

namespace App\Core\Model\Fields;

use App\Core\Model\AbstractModel;
use App\Core\Model\Field;
use App\Core\Model\ModelException;
use App\Infrastructure\DB\Connect;
use App\Infrastructure\DB\DBInterface;

class FieldVarchar implements Field
{
    private string $name;
    private ?string $nevName = null;
    private int $length;
    private bool $isNull;

    public function __construct(AbstractModel $abstractModel,string $name,int $length,bool $isNull = false){
        $abstractModel->migrationBuilder->setName(get_class($abstractModel));

        if(!$abstractModel->migrationBuilder->checkIfColumnExist($name)){
            $this->nevName = $name;
        }else{
            $this->name = $name;
        }

        if ($length > 256) {
            throw new ModelException("Varchar length is grater ten 256 ! ");
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

    public function getLength() : int
    {
        return $this->length;
    }

    public function getFieldName() : string
    {
        return "VARCHAR(".$this->getLength().")";
    }
}