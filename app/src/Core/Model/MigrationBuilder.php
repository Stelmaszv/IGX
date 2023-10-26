<?php

namespace App\Core\Model;

use App\Infrastructure\DB\DBInterface;

class MigrationBuilder
{
    private string $tableName;
    private array $sqlQuery = [];
    private array $fields;
    private DBInterface $engine;

    public function __construct(DBInterface $engine)
    {
        $this->engine = $engine;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function setName(string $name): void
    {
        $fullName = explode('\\', $name);
        $this->tableName = end($fullName);
    }

    private function getColumns(): ?array
    {
        $columns = $this->engine->getQueryLoop("SELECT column_name, column_comment FROM information_schema.columns WHERE table_name = '{$this->tableName}' and column_name != 'id';");

        $columnsArray = array_map(function (array $column) {
            preg_match('/\{(\d+)\}/', $column["COLUMN_COMMENT"], $matches);

            if (isset($matches[1])) {
                $column["COLUMN_COMMENT_INDEX"] = $matches[1];
                return $column;
            }
        }, $columns);

        usort($columnsArray, fn(array $a, array $b) => $a['COLUMN_COMMENT_INDEX'] - $b['COLUMN_COMMENT_INDEX']);

        return $columnsArray;
    }

    public function build(): void
    {
        $data = [
            'columns' => $this->getColumns(),
            'fields' => $this->fields,
        ];

        if ($this->checkIfTableExist() === 0) {
            $this->sqlQuery[] = "CREATE TABLE if NOT EXISTS {$this->engine->escapeString($this->tableName)} (`id` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`id`));";
        }

        foreach ($data['fields'] as $key => $field) {
            if (isset($data['columns'][$key]['COLUMN_NAME'])) {
                $field->setActualName($data['columns'][$key]['COLUMN_NAME']);
            }

            if (!$this->checkIfColumnExist($field->getActualName())) {
                $null = ($field->isNull()) ? 'NULL' : 'NOT NULL';
                $this->sqlQuery[] = "ALTER TABLE {$this->engine->escapeString($this->tableName)} ADD {$this->engine->escapeString($field->getName())} {$field->getFieldName()} {$null} COMMENT '{{{$key}}}';";
            } else {
                $this->checkColumn($field, $data['columns'][$key]);
            }
        }

        $fields = array_map(fn($field) => $field->getName(), $this->fields);

        foreach ($this->getColumns() as $column) {
            if (!in_array($column["COLUMN_NAME"], $fields) && $column["COLUMN_NAME"] !== 'id') {
                $this->sqlQuery[] = "ALTER TABLE {$this->engine->escapeString($this->tableName)} DROP {$this->engine->escapeString($column["COLUMN_NAME"])}";
            }
        }

        file_put_contents('../public/migrate/'.$this->tableName.'.sql', implode("\n", $this->sqlQuery));
    }

    private function checkColumn(Field $field, array $column): void
    {
        $null = ($field->isNull()) ? 'NULL' : 'NOT NULL';
        $this->sqlQuery[] = "ALTER TABLE `{$this->engine->escapeString($this->tableName)}` CHANGE `{$field->getActualName()}` `{$field->getName()}` {$field->getFieldName()} {$null} COMMENT '{$column['COLUMN_COMMENT']}';";
    }

    public function checkIfColumnExist($value): bool
    {
        foreach ($this->getColumns() as $columnName) {
            if ($columnName['COLUMN_NAME'] === $value) {
                return true;
            }
        }

        return false;
    }

    private function checkIfTableExist(): int
    {
        return $this->engine->countSQl('information_schema.tables', [
            [
                'column' => 'table_schema',
                'value' => 'php_docker'
            ],
            [
                'column' => 'table_name',
                'value' => $this->tableName
            ],
        ]);
    }
}
