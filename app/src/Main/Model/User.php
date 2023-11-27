<?php

namespace App\Main\Model;

use App\Main\Entity\UserEntity;
use App\Core\Model\AbstractModel;
use App\Core\Model\Fields\FieldEmail;
use App\Core\Model\Fields\FieldVarchar;

class User extends AbstractModel
{
    protected function initFields(): void
    {
        $this->setEntity(new UserEntity);

        $this->addField(new FieldVarchar(
            'name',
            150
        ));

        $this->addField(new FieldVarchar(
            'password',
            150
        ));

        $this->addField(new FieldEmail(
            'email',
            150,
            true,
            true,
        ));

        $this->addField(new FieldVarchar(
            'role',
            100
        ));
    }

    public function __toString(): string
    {
        return get_class($this);
    }
}
