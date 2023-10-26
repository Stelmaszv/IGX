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

    public function setName(string $name) : void
    {
        $fullName = explode('\\',$name);
        $this->tableName = $fullName[count($fullName)-1];
    }

    private function  getColumns() : ?array
    {
        $columns = $this->engin->getQueryLoop("SELECT column_name, column_comment FROM information_schema.columns WHERE table_name = '$this->tableName' and column_name != 'id';");

        $columnsArray =  array_map(function (array $column) {
            preg_match('/\{(\d+)\}/', $column["COLUMN_COMMENT"], $matches);

            if (isset($matches[1])) {
                $column["COLUMN_COMMENT_INDEX"] = $matches[1];
                return $column;
            }
        }, $columns);

        usort($columnsArray, function (array $a, array $b) {
            return $a['COLUMN_COMMENT_INDEX'] - $b['COLUMN_COMMENT_INDEX'];
        });

        return $columnsArray;
    }

    public function build() : void
    {
        $data = [
            'columns' => $this->getColumns(),
            'fields' => $this->fields,
        ];

        if($this->checkIfTableExist() == 0) {
            $this->sqlQuery[] = "CREATE TABLE if NOT EXISTS " . $this->engin->escapeString($this->tableName) . " (`id` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`));";
        }

        foreach ($data['fields'] as $key => $field){
            if(isset($data['columns'][$key]['COLUMN_NAME'])) {
                $field->setActualName($data['columns'][$key]['COLUMN_NAME']);
            }

            if(!$this->checkIfColumnExist($field->getActualName())) {
                $null = ($field->isNull()) ? 'NULL' : 'NOT NULL';
                $this->sqlQuery[] = 'ALTER TABLE '.$this->engin->escapeString($this->tableName).' ADD `'. $this->engin->escapeString($field->getName()).'` ' . $field->getFieldName() . ' ' . $null.' COMMENT "{'.$key.'}";';
            }else{
                $this->checkColum($field,$data['columns'][$key]);
            }
        }

        $fields = array_map(function ($field) {
            return $field->getName();
        }, $this->fields);

        foreach ($this->getColumns() as $colum){
            if (!in_array($colum["COLUMN_NAME"],$fields) && $colum["COLUMN_NAME"] !== 'id'){
                $this->sqlQuery[] = "ALTER TABLE ".$this->engin->escapeString($this->tableName)." DROP `".$this->engin->escapeString($colum["COLUMN_NAME"])."`";
            }
        }

        file_put_contents('../public/migrate/'.$this->tableName.'.sql', implode("\n", $this->sqlQuery));
    }

    private function checkColum(Field $field,array $column) : void
    {
        $null = ($field->isNull()) ? 'NULL' : 'NOT NULL';
        $this->sqlQuery[] = 'ALTER TABLE `'.$this->engin->escapeString($this->tableName).'` CHANGE `'.$field->getActualName().'` `' . $field->getName(). '` ' . $field->getFieldName(). ' ' . $null.' COMMENT "'.$column['COLUMN_COMMENT'].'";';
    }

    public function checkIfColumnExist($value) : bool
    {
        foreach ($this->getColumns() as $columName){
            if($columName['COLUMN_NAME'] === $value){
                return true;
            }
        }

        return false;
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