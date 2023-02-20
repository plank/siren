<?php

namespace Plank\Siren\Builders\EntityRelationDiagram;

class Entity
{
    /**
     * @param  array<Attribute>  $attributes
     */
    public function __construct(
        public readonly string $name,
        public readonly array $attributes = []
    ) {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function addAttribute(Attribute $attribute): self
    {
        return new self(
            $this->name,
            [...$this->attributes, $attribute]
        );
    }

    public function __toString(): string
    {
        if (empty($this->attributes)) {
            return $this->label();
        }

        $md = $this->label()." {\n";

        foreach ($this->attributes as $attribute) {
            $md .= $attribute;
        }

        return $md.'}';
    }

    public function label(): string
    {
        return '"'.$this->name.'"';
    }
}
