<?php

namespace Plank\Siren\Builders\ClassDiagram;

class Argument
{
    public function __construct(
        protected readonly string $name,
        protected readonly ?Type $type = null
    ) {
    }

    public function __toString(): string
    {
        $md = '';

        if ($this->type) {
            $md = $this->type.' ';
        }

        $md .= $this->name;

        return $md;
    }
}
