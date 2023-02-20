<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Connection;
use Plank\Siren\Builders\ClassDiagram\Enums\Multiplicity;
use Plank\Siren\Builders\ClassDiagram\Enums\Strength;

class Relation
{
    public function __construct(
        public readonly Symbol $symbolA,
        public readonly ?Connection $connectionA,
        public readonly ?Multiplicity $multiplicityA,
        public readonly Symbol $symbolB,
        public readonly ?Connection $connectionB,
        public readonly ?Multiplicity $multiplicityB,
        public readonly Strength $strength = Strength::ASSOCIATION,
        public readonly ?string $name = null
    ) {
    }

    public static function make(Symbol $symbolA, Symbol $symbolB): self
    {
        return new self($symbolA, null, null, $symbolB, null, null, Strength::ASSOCIATION, null);
    }

    public function key(): string
    {
        return $this->symbolA->name.$this->name.$this->symbolB->name;
    }

    public function name(string $name): self
    {
        return new self(
            $this->symbolA,
            $this->connectionA,
            $this->multiplicityA,
            $this->symbolB,
            $this->connectionB,
            $this->multiplicityB,
            $this->strength,
            $name
        );
    }

    public function connection(Symbol $symbol, ?Connection $connection): self
    {
        if ($symbol == $this->symbolA) {
            return new self(
                $this->symbolA,
                $connection,
                $this->multiplicityA,
                $this->symbolB,
                $this->connectionB,
                $this->multiplicityB,
                $this->strength,
                $this->name
            );
        }

        return new self(
            $this->symbolA,
            $this->connectionA,
            $this->multiplicityA,
            $this->symbolB,
            $connection,
            $this->multiplicityB,
            $this->strength,
            $this->name
        );
    }

    public function multiplicity(Symbol $symbol, ?Multiplicity $multiplicity): self
    {
        if ($symbol == $this->symbolA) {
            return new self(
                $this->symbolA,
                $this->connectionA,
                $multiplicity,
                $this->symbolB,
                $this->connectionB,
                $this->multiplicityB,
                $this->strength,
                $this->name
            );
        }

        return new self(
            $this->symbolA,
            $this->connectionA,
            $this->multiplicityA,
            $this->symbolB,
            $this->connectionB,
            $multiplicity,
            $this->strength,
            $this->name
        );
    }

    public function strength(Strength $strength): self
    {
        return new self(
            $this->symbolA,
            $this->connectionA,
            $this->multiplicityA,
            $this->symbolB,
            $this->connectionB,
            $this->multiplicityB,
            $strength,
            $this->name
        );
    }

    public function __toString(): string
    {
        $md = $this->symbolA->name.' ';

        if ($this->multiplicityA) {
            $md .= '"'.$this->multiplicityA->value.'" ';
        }

        if ($this->connectionA) {
            $md .= $this->connectionA->left();
        }

        $md .= $this->strength->value;

        if ($this->connectionB) {
            $md .= $this->connectionB->right();
        }

        if ($this->multiplicityB) {
            $md .= '"'.$this->multiplicityB->value.'"';
        }

        $md .= ' '.$this->symbolB->name;

        if ($this->name) {
            $md .= ' : '.$this->name;
        }

        return $md;
    }
}
