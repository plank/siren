<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Exceptions\ClassException;
use Plank\Siren\Builders\ClassDiagram\Exceptions\RelationException;

class ClassDiagram
{
    /**
     * @param  array<UmlClass>  $classes
     * @param  array<Relation>  $relations
     */
    public function __construct(
        protected ?string $title = null,
        protected array $classes = [],
        protected array $relations = []
    ) {
    }

    public function addClass(UmlClass $class): self
    {
        if ($this->hasClass($class)) {
            throw new ClassException('You cannot have duplicate classes in the same Class Diagram.');
        }

        $this->classes[$class->name] = $class;

        return $this;
    }

    public function hasClass(UmlClass $class): bool
    {
        return isset($this->classes[$class->name]);
    }

    public function removeClass(UmlClass $class): self
    {
        unset($this->classes[$class->name]);

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

        foreach ($this->classes as $class) {
            $md .= $class."\n";
        }

        foreach ($this->relations as $relation) {
            $md .= $relation."\n";
        }

        return $md;
    }
}
