<?php

namespace Plank\Siren\Builders\Flowchart;

use Plank\Siren\Builders\Flowchart\Enums\Shape;
use Plank\Siren\Builders\Flowchart\Exceptions\IdentifierException;
use Plank\Siren\Traits\Escaped;
use Plank\Siren\Traits\HasIdentifier;
use Plank\Siren\Traits\Indented;

class Node
{
    use Indented;
    use Escaped;
    use HasIdentifier;

    public function __construct(
        public readonly string $id,
        public readonly ?string $text = null,
        public readonly Shape $shape = Shape::SQUARE
    ) {
        if ($chars = $this->illegalCharacters($id)) {
            throw new IdentifierException("Illegal Characters in Subgraph $id [Illegal: $chars]");
        }
    }

    public static function make(string $id): self
    {
        return new self($id);
    }

    public function text(string $text): self
    {
        return new self($this->id, $text, $this->shape);
    }

    public function shape(Shape $shape): self
    {
        return new self($this->id, $this->text, $shape);
    }

    public function __toString(): string
    {
        $md = $this->indentation().$this->id;

        if ($this->shape === Shape::SQUARE && $this->text === null) {
            return $md."\n";
        }

        if ($this->shape !== Shape::SQUARE && $this->text === null) {
            return $md.$this->shape->fill($this->id)."\n";
        }

        return $md.$this->shape->fill($this->escape($this->text))."\n";
    }
}
