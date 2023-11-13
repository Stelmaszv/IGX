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
        $this->setEntity(CatsEntity::class);
        $this->addField(new FieldVarchar(
            'name',
            256,
            true
        ));
        $this->addField(new FieldINT(
            'counter',
            255,
            true
        ));

        $this->addField(new FieldTEXT(
            'description',
            100,
            true
        ));
    }

    public function __toString(): string
    {
        return get_class($this);
    }
}