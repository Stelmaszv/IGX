<?php

namespace App\Core\Model;

use App\Infrastructure\DB\DBInterface;

class MigrationBuilder
{
    private string $tableName;
    private array $sqlQuery = [];
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
        if($this->checkIfTableExist() == 0) {
            $this->sqlQuery[] = "CREATE TABLE if NOT EXISTS " . $this->engin->escapeString($this->tableName) . " (`id` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`));";
        }

        foreach ($this->fields as $colum){
            if(!$this->checkIfColumnExist($colum->getName()) || !$this->checkIfColumnExist($colum->getNevName())) {
                $null = ($colum->isNull()) ? 'NULL' : 'NOT NULL';
                $this->sqlQuery[] = "ALTER TABLE ".$this->engin->escapeString($this->tableName)." ADD `". $this->engin->escapeString($colum->getName())."` " . $colum->getFieldName() . " " . $null.";";
            }else{
                $this->checkColum($colum->getName() , $colum);
            }
        }

        $fields = array_map(function ($field) {
            return $field->getName();
        }, $this->fields);

        foreach ($this->showColumns() as $colum){
            if (!in_array($colum["COLUMN_NAME"],$fields) && $colum["COLUMN_NAME"] !== 'id'){
                $this->sqlQuery[] = "ALTER TABLE ".$this->engin->escapeString($this->tableName)." DROP `".$this->engin->escapeString($colum["COLUMN_NAME"])."`";
            }
        }

        echo '<pre>';
        var_dump($this->sqlQuery);
        echo '</pre>';

        file_put_contents('../public/migrate/'.$this->tableName.'.sql', implode("\n", $this->sqlQuery));
    }

    private function checkColum(string $columName, Field $colum) : void
    {
        $query = $this->engin->getQueryLoop("SHOW COLUMNS FROM ".$this->tableName.";");
        foreach ($query as $columNameEl){
            if($columNameEl['Field'] === $columName){
                $null = ($colum->isNull()) ? 'NULL' : 'NOT NULL';
                $this->sqlQuery[] = "ALTER TABLE ".$columNameEl['Field']." ".$this->engin->escapeString($this->tableName)." CHANGE `". $this->engin->escapeString($colum->getName())."` " . $colum->getNevName(). " " . $null.";";
            }

        }
    }

    public function setName(string $name) : void
    {
        $fullName = explode('\\',$name);
        $this->tableName = $fullName[count($fullName)-1];
    }

    public function getColumValue(string $columName) : ?array
    {
        $query = $this->engin->getQueryLoop("SHOW COLUMNS FROM ".$this->tableName.";");
        foreach ($query as $columNameEl) {
            if ($columNameEl['Field'] === $columName) {
                return $columNameEl;
            }

        }

        return null;
    }

    public function checkIfColumnExist($value){
        foreach ($this->showColumns() as $columName){
            if($columName['COLUMN_NAME'] === $value){
                return true;
            }
        }

        return false;
    }

    private function showColumns() : array
    {
        return $this->engin->getQueryLoop("SELECT column_name FROM information_schema.columns WHERE table_name = '$this->tableName'");
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
                'value' => $this->tableName
            ],
        ]);
    }


}