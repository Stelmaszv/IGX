<?php

namespace App\Main\Model;

use App\Core\Model\AbstractModel;
use App\Main\Entity\RegisterEntity;
use App\Core\Model\Fields\FieldVarchar;

class Register  extends AbstractModel
{
    protected function initFields(): void
    {
        $this->setEntity(new RegisterEntity);
        $this->addField(new FieldVarchar(
            'time',
            100
        ));
    }

    public function __toString(): string
    {
        return get_class($this);
    }
}
