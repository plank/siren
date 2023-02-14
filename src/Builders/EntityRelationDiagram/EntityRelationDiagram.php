<?php

namespace Plank\Siren\Builders\EntityRelationDiagram;

use Plank\Siren\Builders\EntityRelationDiagram\Exceptions\EntityException;
use Plank\Siren\Builders\EntityRelationDiagram\Exceptions\RelationException;

class EntityRelationDiagram
{
    /**
     * @param array<Entity> $entities
     * @param array<Relation> $relations
     */
    public function __construct(
        protected ?string $title = null,
        protected array $entities = [],
        protected array $relations = []
    ) {
    }

    public function addEntity(Entity $entity): self
    {
        if ($this->hasEntity($entity)) {
            throw new EntityException('You cannot have duplicate entities in the same ER Diagram.');
        }

        $this->entities[$entity->name] = $entity;

        return $this;
    }

    public function hasEntity(Entity $entity): bool
    {
        return isset($this->entities[$entity->name]);
    }

    public function removeEntity(Entity $entity): self
    {
        unset($this->entities[$entity->name]);

        return $this;
    }

    public function addRelation(Relation $relation): self
    {
        if ($this->hasRelation($relation)) {
            throw new RelationException('You cannot have duplicate relations in the same ER Diagram.');
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

        $md .= "erDiagram\n";

        foreach ($this->entities as $entity) {
            $md .= $entity."\n";
        }

        foreach ($this->relations as $relation) {
            $md .= $relation."\n";
        }

        return $md;
    }
}
