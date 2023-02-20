<?php

namespace Plank\Siren\Builders\EntityRelationDiagram;

use Plank\Siren\Builders\EntityRelationDiagram\Enums\Key;

class Attribute
{
    /**
     * @param  array<Attribute>  $attributes
     */
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly ?Key $key = null,
        public readonly string $comment = ''
    ) {
    }

    public static function make(string $name, string $type)
    {
        return new self($name, $type);
    }

    public function key(Key $key): self
    {
        return new self(
            $this->name,
            $this->type,
            $key,
            $this->comment
        );
    }

    public function comment(string $comment): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->key,
            $comment
        );
    }

    public function __toString(): string
    {
        $md = $this->type.' '.$this->name;

        if ($this->key) {
            $md .= ' '.$this->key->value;
        }

        if ($this->comment) {
            $md .= ' "'.$this->comment.'"';
        }

        return $md."\n";
    }
}
