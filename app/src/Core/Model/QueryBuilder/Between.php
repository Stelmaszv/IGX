<?php

namespace App\Core\Model\QueryBuilder;

class Between implements WhereInterFace
{
    private string $column;
    private string $sqlOperator;
    private int $min;
    private int $max;

    function __construct(string $column, int $min, int $max,string $sqlOperator)
    {
        $this->column = $column;
        $this->min = $min;
        $this->max = $max;
        $this->sqlOperator = $sqlOperator;
    }

    public function add(): string
    {
        return $this->column.' BETWEEN '.$this->min.' AND '.$this->max;
    }

    public function getSQLOperator(): string
    {
        return $this->sqlOperator;
    }

    public function getOperator(): string
    {
        return '';
    }
}