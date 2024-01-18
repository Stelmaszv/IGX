<?php

namespace App\Main\Model;

use App\Settings\RolesList;
use App\Main\Entity\UserEntity;
use App\Core\Model\AbstractModel;
use App\Core\Model\Fields\FieldEmail;
use App\Core\Model\Fields\SelectValue;
use App\Core\Model\Fields\FieldVarchar;
use App\Core\Model\Fields\FieldCollection;
use App\Core\Model\SelectValues\RolesListValues;

class User extends AbstractModel
{
    protected function initFields(): void
    {
        $this->setEntity(new UserEntity);

        $this->addField(new FieldVarchar(
            'name',
            150,
            false,
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

        $this->addField(new SelectValue(
            'role',
            250,
            RolesListValues::VALUES,
            false,
        ));

        $this->addField(new FieldVarchar(
            'salt',
            200
        ));
    }

    public function __toString(): string
    {
        return get_class($this);
    }
}
