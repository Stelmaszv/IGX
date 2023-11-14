<?php

namespace App\Core\Model\QueryBuilder;

class Query
{
    private ?string $sql =' WHERE ';
    private array $queryArray;

    public function setQueryArray(): void
    {
        $count = 0;
        foreach ($this->queryArray as $query) {
            if( $count !== 0 ) {
                $this->sql .= $query->getSQLOperator() . ' ' . $query->add() . ' ';
            }else{
                $this->sql .= $query->add(). ' ';
            }

            $count++;
        }
    }

    public function addWhere(WhereInterFace $where) : void
    {
        $this->queryArray[] = $where;
    }

    public function getSql(): string
    {
        return $this->sql;
    }
}