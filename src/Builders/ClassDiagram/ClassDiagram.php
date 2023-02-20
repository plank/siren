<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Direction;
use Plank\Siren\Builders\ClassDiagram\Exceptions\SymbolException;
use Plank\Siren\Builders\ClassDiagram\Exceptions\RelationException;

class ClassDiagram
{
    /**
     * @param  array<Symbol>  $symbols
     * @param  array<Relation>  $relations
     */
    public function __construct(
        protected ?string $title = null,
        protected array $symbols = [],
        protected array $relations = [],
        protected Direction $direction = Direction::TOP_DOWN
    ) {
    }

    public function direction(Direction $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function addSymbol(Symbol $symbol): self
    {
        if ($this->hasSymbol($symbol)) {
            throw new SymbolException('You cannot have duplicate classes in the same Class Diagram.');
        }

        $this->symbols[$symbol->name] = $symbol;

        return $this;
    }

    public function hasSymbol(Symbol $symbol): bool
    {
        return isset($this->symbols[$symbol->name]);
    }

    public function removeSymbol(Symbol $symbol): self
    {
        unset($this->symbols[$symbol->name]);

        return $this;
    }

    public function addRelation(Relation $relation): self
    {
        if ($this->hasRelation($relation)) {
            throw new RelationException('You cannot have duplicate relations in the same Class Diagram.');
        }

        $this->relations[$relation->key()] = $relation;

        return $this;
    }

    public function hasRelation(Relation $relation): bool
    {
        return isset($this->relations[$relation->key()]);
    }

    public function removeRelation(Relation $relation): self
    {
        unset($this->relations[$relation->key()]);

        return $this;
    }

    public function __toString(): string
    {
        $md = '';

        if ($this->title) {
            $md .= "---\n";
            $md .= "title: $this->title\n";
            $md .= "---\n";
        }

        $md .= "classDiagram\n";

        if ($this->direction !== Direction::TOP_DOWN) {
            $md .= "direction ".$this->direction->value."\n";
        }

        foreach ($this->symbols as $symbol) {
            $md .= $symbol."\n";
        }

        foreach ($this->relations as $relation) {
            $md .= $relation."\n";
        }

        return $md;
    }
}
