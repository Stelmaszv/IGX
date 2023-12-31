<?php

namespace App\Core\Model\Fields;

use App\Core\Model\Field;
use App\Core\Model\FieldValidate;
use App\Core\Model\ModelException;
use App\Infrastructure\DB\DBInterface;

class FieldINT implements Field
{
    use FieldValidate;
    private string $name;
    private ?int $length;
    private bool $isNull;
    private ?int $value;
    private ?string $actualName = null;
    private DBInterface $engine; 
    private bool $isUniqe; 

    public function __construct(
        string $name,
        ?int $length = null,
        bool $isNull = true,
        bool $isUniqe = false
    ) {
        if ($length !== null && $length > 255) {
            throw new ModelException("Int length cannot exceed 255.");
        }

        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
        $this->isUniqe = $isUniqe;
    }

    public function setEngine(DBInterface $engine){
        $this->engine = $engine;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value) : void
    {
        $this->value = $value;
    }

    public function setActualName(string $name): void
    {
        $this->actualName = $name;
    }

    public function getActualName(): ?string
    {
        return $this->actualName;
    }

    public function isNull(): bool
    {
        return $this->isNull;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getFieldName(): string
    {
        if ($this->getLength() !== null) {
            return "INT({$this->getLength()})";
        } else {
            return "INT";
        }
    }

    public function getIsUniqe()
    {
        return $this->isUniqe;
    }
}
