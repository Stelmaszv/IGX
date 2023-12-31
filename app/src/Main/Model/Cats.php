<?php

namespace App\Main\Model;

use App\Core\Model\AbstractModel;
use App\Core\Model\Fields\FieldINT;
use App\Core\Model\Fields\FieldText;
use App\Core\Model\Fields\FieldVarchar;
use App\Main\Entity\CatsEntity;

class Cats extends AbstractModel
{
    protected function initFields(): void
    {
        $this->setEntity(new CatsEntity);
        $this->addField(new FieldVarchar(
            'name',
            256
        ));
        $this->addField(new FieldINT(
            'counter',
            255
        ));

        $this->addField(new FieldTEXT(
            'description',
            100
        ));
    }

    public function __toString(): string
    {
        return get_class($this);
    }
}