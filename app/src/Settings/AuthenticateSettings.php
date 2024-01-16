<?php

namespace App\Settings;

use App\Main\Model\User;

class AuthenticateSettings
{
    public const TABLE = 'App\Main\Model\User';
    public const ENTITY = 'App\Main\Entity\UserEntity';
    public const MODEL = User::class;
    public const LOGINBY = 'email';
    public const SALD = 32;
}
