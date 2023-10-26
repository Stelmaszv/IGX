<?php

namespace App\Core\Model\Fields;

use App\Core\Model\Field;
use App\Core\Model\ModelException;

class FieldText implements Field
{
    private string $name;
    private ?int $length;
    private bool $isNull;
    private ?string $actualName = null;

    public function __construct(
        string $name,
        ?int $length = null,
        bool $isNull = false
    ) {
        if ($length !== null && $length > 256) {
            throw new ModelException("Text length cannot exceed 256 characters.");
        }

        $this->name = $name;
        $this->length = $length;
        $this->isNull = $isNull;
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
            return "TEXT(" . $this->getLength() . ")";
        } else {
            return "TEXT";
        }
    }
}
