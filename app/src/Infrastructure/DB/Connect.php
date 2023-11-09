<?php

namespace App\Infrastructure\DB;

use App\Infrastructure\DB\Engines\MysqliEngine;
use App\Infrastructure\DB\Engines\PDOEngine;
use App\Settings\DBSettings;

class Connect
{
    private ?DBInterface $engine;
    private array $engines = ['PDO', 'MYSQLI'];

    public function __construct()
    {
        $this->engine = $this->setEngine();
    }

    private function setEngine(): ?DBInterface
    {
        if (!in_array(DBSettings::ENGINE, $this->engines)) {
            throw new DBException('Invalid Engine!');
        }

        switch(DBSettings::ENGINE) {
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