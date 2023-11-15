<?php

namespace App\Core\Model;

interface Field
{
    public function getFieldName() : string;
    public function validate(mixed $value): void;
}