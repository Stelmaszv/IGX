<?php

namespace App\Main\Model;

use App\Core\Model\AbstractModel;
use App\Main\Model\Fields\FieldVarchar;

class Cats extends AbstractModel
{
    protected function initFields(): void
    {
        $this->addField(new FieldVarchar(
            'name',
            '256'
        ));
    }
}