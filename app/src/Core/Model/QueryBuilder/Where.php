<?php

namespace App\Core\Model\QueryBuilder;

use App\Core\Model\ModelException;

class Where implements WhereInterFace
{
    private string $column;
    private string $value;
    private string $sqlOperator;
    private string $operator;
    private const SALOperators = ['and','or'];

    function __construct(string $column, string $value,string $operator,string $sqlOperator)
    {
        if (!in_array($sqlOperator,self::SALOperators)){
            throw new ModelException("Invalid sql operator !");
        }

        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
        $this->sqlOperator = $sqlOperator;
    }

    public function add(): string
    {
        return $this->column." ".$this->operator." '".$this->value."'";
    }

    public function getSQLOperator(): string
    {
        return $this->sqlOperator;
    }

    public function getOperator(): string
    {
        return $this->sqlOperator;
    }
}