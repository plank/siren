<?php

namespace Plank\Siren\Builders\EntityRelationDiagram;

use Plank\Siren\Builders\EntityRelationDiagram\Enums\Cardinality;
use Plank\Siren\Builders\EntityRelationDiagram\Enums\Identification;

class Relation
{
    public function __construct(
        public readonly Entity $entityA,
        public readonly Cardinality $cardinalityA,
        public readonly Entity $entityB,
        public readonly Cardinality $cardinalityB,
        public readonly string $name,
        public readonly Identification $identification = Identification::IDENTIFYING
    ) {
    }

    public function key(): string
    {
        return $this->entityA->name.$this->name.$this->entityB->name;
    }

    public static function make(string $name, Entity $entityA, Entity $entityB): self
    {
        return new self(
            $entityA,
            Cardinality::ONLY_ONE,
            $entityB,
            Cardinality::ONLY_ONE,
            $name
        );
    }

    public function cardinality(Entity $entity, Cardinality $cardinality): self
    {
        if ($entity == $this->entityA) {
            return new self(
                $this->entityA,
                $cardinality,
                $this->entityB,
                $this->cardinalityB,
                $this->name
            );
        }

        return new self(
            $this->entityA,
            $this->cardinalityA,
            $this->entityB,
            $cardinality,
            $this->name
        );
    }

    public function identification(Identification $identification): self
    {
        return new self(
            $this->entityA,
            $this->cardinalityA,
            $this->entityB,
            $this->cardinalityB,
            $this->name,
            $identification
        );
    }

    public function __toString(): string
    {
        return $this->entityA->label().' '.$this->relation().' '.$this->entityB->label().' : '.$this->label();
    }

    protected function relation()
    {
        return $this->cardinalityA->left().$this->identification->value.$this->cardinalityB->right();
    }

    public function label(): string
    {
        return '"'.$this->name.'"';
    }
}
