<?php

namespace App\Core\Model;

use App\Infrastructure\DB\DBInterface;

class MigrationBuilder
{
    private string $tableName;
    private array $sqlQuery = [];
    private array $fields;
    private DBInterface $engine;
    private Table $table;

    public function __construct(DBInterface $engine)
    {
        $this->engine = $engine;
        $this->table = new Table($engine);
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function setName(string $name): void
    {
        $fullName = explode('\\', $name);
        $this->tableName = end($fullName);
        $this->table->setTable($this->tableName);
    }

    public function build(): void
    {
        $data = [
            'columns' => $this->table->getColumns(),
            'fields' => $this->fields,
        ];

        if ($this->table->checkIfTableExist() === 0) {
            $this->sqlQuery[] = "CREATE TABLE if NOT EXISTS {$this->engine->escapeString($this->tableName)} (`id` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`));";
        }

        foreach ($data['fields'] as $key => $field) {

            if (isset($data['columns'][$key]['COLUMN_NAME'])) {
                $field->setActualName($data['columns'][$key]['COLUMN_NAME']);
            }

            if (!$this->table->checkIfColumnExist($field->getActualName())) {
                $null = ($field->isNull()) ? 'NULL' : 'NOT NULL';
                $this->sqlQuery[] = "ALTER TABLE {$this->engine->escapeString($this->tableName)} ADD {$this->engine->escapeString($field->getName())} {$field->getFieldName()} {$null} COMMENT '{{{$key}}}';";
            } else {
                $this->sqlQuery[] = $this->table->checkColumn($field, $data['columns'][$key]);
            }
        }

        $fields = array_map(fn($field) => $field->getName(), $this->fields);

        foreach ($this->table->getColumns() as $column) {
            if (!in_array($column["COLUMN_NAME"], $fields) && $column["COLUMN_NAME"] !== 'id') {
                $this->sqlQuery[] = "ALTER TABLE {$this->engine->escapeString($this->tableName)} DROP {$this->engine->escapeString($column["COLUMN_NAME"])}";
            }
        }

        file_put_contents('../public/migrate/'.$this->tableName.'.sql', implode("\n", $this->sqlQuery));
    }

}
