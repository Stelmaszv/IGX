<?php

namespace App\Main\Model\Data;

use App\Core\Model\AbstractModel;
use App\Main\Model\Fields\FieldVarchar;

class Works extends AbstractModel
{
    protected function initFields(): void
    {
        $this->addField(new FieldVarchar(
            'name',
            '256'
        ));
    }
}