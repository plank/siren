<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Connection;
use Plank\Siren\Builders\ClassDiagram\Enums\Multiplicity;
use Plank\Siren\Builders\ClassDiagram\Enums\Strength;

class Relation
{
    public function __construct(
        public readonly UmlClass $classA,
        public readonly ?Connection $connectionA,
        public readonly ?Multiplicity $multiplicityA,
        public readonly UmlClass $classB,
        public readonly ?Connection $connectionB,
        public readonly ?Multiplicity $multiplicityB,
        public readonly Strength $strength = Strength::ASSOCIATION,
        public readonly ?string $name = null
    ) {
    }

    public function key(): string
    {
        return $this->classA->name.$this->name.$this->classB->name;
    }

    public static function make(UmlClass $classA, UmlClass $classB): self
    {
        return new self($classA, null, null, $classB, null, null, Strength::ASSOCIATION, null);
    }

    public function name(string $name)
    {
        return new self(
            $this->classA,
            $this->connectionA,
            $this->multiplicityA,
            $this->classB,
            $this->connectionB,
            $this->multiplicityB,
            $this->strength,
            $name
        );
    }

    public function connection(UmlClass $class, ?Connection $connection)
    {
        if ($class == $this->classA) {
            return new self(
                $this->classA,
                $connection,
                $this->multiplicityA,
                $this->classB,
                $this->connectionB,
                $this->multiplicityB,
                $this->strength,
                $this->name
            );
        }

        return new self(
            $this->classA,
            $this->connectionA,
            $this->multiplicityA,
            $this->classB,
            $connection,
            $this->multiplicityB,
            $this->strength,
            $this->name
        );
    }

    public function multiplicity(UmlClass $class, ?Multiplicity $multiplicity)
    {
        if ($class == $this->classA) {
            return new self(
                $this->classA,
                $this->connectionA,
                $multiplicity,
                $this->classB,
                $this->connectionB,
                $this->multiplicityB,
                $this->strength,
                $this->name
            );
        }

        return new self(
            $this->classA,
            $this->connectionA,
            $this->multiplicityA,
            $this->classB,
            $this->connectionB,
            $multiplicity,
            $this->strength,
            $this->name
        );
    }

    public function association()
    {
        return new self(
            $this->classA,
            $this->connectionA,
            $this->multiplicityA,
            $this->classB,
            $this->connectionB,
            $this->multiplicityB,
            Strength::ASSOCIATION,
            $this->name
        );
    }

    public function dependency()
    {
        return new self(
            $this->classA,
            $this->connectionA,
            $this->multiplicityA,
            $this->classB,
            $this->connectionB,
            $this->multiplicityB,
            Strength::DEPENDENCY,
            $this->name
        );
    }

    public function __toString(): string
    {
        $md = $this->classA->name.' ';

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

        $md .= ' '.$this->classB->name;

        if ($this->name) {
            $md .= ' : '.$this->label();
        }

        return $md;
    }

    public function label(): string
    {
        return '"'.$this->name.'"';
    }
}
