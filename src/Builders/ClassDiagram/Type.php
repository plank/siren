<?php

namespace Plank\Siren\Builders\ClassDiagram;

class Type
{
    public function __construct(
        protected readonly string $type,
        protected readonly ?string $of = null
    ) {
    }

    public static function make(string $type): self
    {
        return new self($type);
    }

    public static function generic(string $type, string $of): self
    {
        return new self($type, $of);
    }

    public function __toString(): string
    {
        if ($this->of === null) {
            return $this->type;
        }

        return $this->type.'~'.$this->of.'~';
    }
}
