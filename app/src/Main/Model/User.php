<?php

namespace App\Main\Model;

use App\Core\Model\AbstractModel;
use App\Core\Model\Fields\FieldVarchar;

class User extends AbstractModel
{
    protected function initFields(): void
    {
        $this->addField(new FieldVarchar(
            $this,
            'names',
            256
        ));
    }
}