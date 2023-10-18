<?php

namespace App\Core\Model;

use App\Infrastructure\DB\DBInterface;
use App\Settings\DBSettings;

class MigrationBuilder
{
    private string $name;
    private string $sql;
    private array $fields;
    private DBInterface $engin;

    public function __construct(DBInterface $engin)
    {
        $this->engin = $engin;
    }

    public function  setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function build() : void
    {
        $this->sql = "CREATE TABLE if NOT EXISTS ".$this->engin->escapeString($this->name)." (`id` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`))";

        foreach ($this->fields as $colum){
            if(!$this->checkIfColumnExist($colum->getName())) {
                $null = ($colum->isNull()) ? 'NULL' : 'NOT NULL';
                $this->sql .= " ALTER TABLE ".$this->engin->escapeString($this->name)." ADD `".$this->engin->escapeString($colum->getName())."` VARCHAR(".intval($colum->getLength()).") ".$null;
            }
        }
    }

    public function setName(string $name) : void
    {
        $fullName = explode('\\',$name);
        $this->name = $fullName[count($fullName)-1];
    }

    private function checkIfColumnExist($value){
        foreach ($this->showColumns() as $columName){
            if($columName['COLUMN_NAME'] === $value){
                return true;
            }
        }

        return false;
    }

    private function showColumns() : array
    {
        return $this->engin->getQueryLoop("SELECT column_name FROM information_schema.columns WHERE table_name = '$this->name'");
    }

    private function checkIfTableExist() : int
    {
        return $this->engin->countSQl('information_schema.tables',[
            [
                'colum' => 'table_schema',
                'value' => 'php_docker'
            ],
            [
                'colum' => 'table_name',
                'value' => $this->name
            ],
        ]);
    }


}