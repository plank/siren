<?php

namespace Plank\Siren\Builders\ClassDiagram;

class Type
{
    public function __construct(
        protected readonly string $type
    ) {
    }

    public static function make(string $type): self
    {
        return new self($type);
    }

    public function __toString(): string
    {
        return preg_replace('/[\<\>]/', '~', $this->type);
    }
}
