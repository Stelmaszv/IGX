<?php

namespace App\Core\Model;

use App\Infrastructure\DB\DBInterface;
use App\Settings\DBSettings;

class Table
{
    private DBInterface $engine;
    private string $tableName;

    public function __construct(DBInterface $engine)
    {
        $this->engine = $engine;
    }

    public function setTable(string $table):void
    {
        $this->tableName = $table;
    }

    public function getColumns(): ?array
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

    public function updateColumn(Field $field, array $column): string
    {
        $null = ($field->isNull()) ? 'NULL' : 'NOT NULL';
        return "ALTER TABLE `{$this->engine->escapeString($this->tableName)}` CHANGE `{$field->getActualName()}` `{$field->getName()}` {$field->getFieldName()} {$null} COMMENT '{$column['COLUMN_COMMENT']}';";
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

    public function checkIfTableExist(): int
    {
        return $this->engine->countSQl('information_schema.tables', [
            [
                'column' => 'table_schema',
                'value' => DBSettings::DBNAME
            ],
            [
                'column' => 'table_name',
                'value' => $this->tableName
            ],
        ]);
    }
}