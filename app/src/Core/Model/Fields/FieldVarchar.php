<?php

namespace App\Core\Model\Fields;

use App\Core\Model\Field;
use App\Core\Model\FieldValidate;
use App\Core\Model\ModelException;

class FieldVarchar implements Field
{
    use FieldValidate;
    private string $name;
    private ?string $actualName = null;
    private int $length;
    private bool $isNull;
    private ?string $value;

    public function __construct(string $name, int $length, bool $isNull = false)
    {
        if ($length > 256) {
            throw new ModelException("Varchar length cannot exceed 256 characters.");
        }

        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
    }

    public function setActualName(string $name): void
    {
        $this->actualName = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
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

    public function getLength(): int
    {
        return $this->length;
    }

    public function getFieldName(): string
    {
        return "VARCHAR({$this->getLength()})";
    }
}