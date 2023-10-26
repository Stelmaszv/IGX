<?php

namespace App\Infrastructure\DB;

use App\Infrastructure\DB\Engines\MysqliEngine;
use App\Infrastructure\DB\Engines\PDOEngine;
use App\Settings\DBSettings;

class Connect
{
    private ?DBInterface $engine;
    private array $engines = ['PDO', 'MYSQLI'];

    private function __construct()
    {
        $this->engine = $this->setEngine(DBSettings::ENGINE);
    }

    private function setEngine(string $engine): ?DBInterface
    {
        if (!in_array($engine, $this->engines)) {
            throw new DBException('Invalid Engine!');
        }

        switch($engine) {
            case 'PDO':
                return new PDOEngine();
            case 'MYSQLI':
                return new MysqliEngine();
        }

        return null;
    }

    public function getEngine(): ?DBInterface
    {
        return $this->engine;
    }

    public static function getInstance(): self
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static();
        }

        return $instance;
    }
}