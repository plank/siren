<?php

namespace Plank\Siren\Builders\EntityRelationDiagram;

use Plank\Siren\Builders\EntityRelationDiagram\Enums\Key;

class Attribute
{
    /**
     * @param  array<Attribute>  $attributes
     */
    public function __construct(
        public readonly string $type,
        public readonly string $name,
        public readonly string $comment = '',
        public readonly ?Key $key = null
    ) {
    }

    public static function make(string $type, string $name, string $comment = '', ?Key $key = null)
    {
        return new self($type, $name, $comment, $key);
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
