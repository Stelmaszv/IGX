<?php

namespace App\Core\Model\QueryBuilder;

interface WhereInterFace
{
    public function add(): string;
    public function getSQLOperator(): string;
    public function getOperator(): string;
}