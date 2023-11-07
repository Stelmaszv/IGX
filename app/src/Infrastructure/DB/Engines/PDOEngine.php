<?php

namespace App\Infrastructure\DB\Engines;

use App\Infrastructure\DB\DBInterface;
use App\Infrastructure\DB\DBException;
use App\Settings\DBSettings;
use PDO;
use PDOException;

class PDOEngine implements DBInterface
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DBSettings::HOST . ";dbname=" . DBSettings::DBNAME,
                DBSettings::USERNAME,
                DBSettings::PASSWORD
            );

            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            throw new DBException($exception->getMessage());
        }
    }

    public function getQueryLoop(string $sql, $array = []): array
    {
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute($array);

            $records = [];
            while ($row = $query->fetch()) {
                $records[] = $row;
            }

            return $records;
        } catch (PDOException $exception) {
            throw new DBException($exception->getMessage());
        }
    }

    public function runQuery(string $sql, string $message, $array = []): string
    {
        try {
            $query = $this->pdo->prepare($sql);
            $success = $query->execute($array);

            if ($success) {
                return $message;
            }
        } catch (PDOException $exception) {
            throw new DBException($exception->getMessage());
        }

        return '';
    }
}