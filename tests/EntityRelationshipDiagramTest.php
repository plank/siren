<?php

use Plank\Siren\Builders\EntityRelationDiagram\Attribute;
use Plank\Siren\Builders\EntityRelationDiagram\Entity;
use Plank\Siren\Builders\EntityRelationDiagram\Enums\Cardinality;
use Plank\Siren\Builders\EntityRelationDiagram\Enums\Key;
use Plank\Siren\Builders\EntityRelationDiagram\Exceptions\EntityException;
use Plank\Siren\Builders\EntityRelationDiagram\Exceptions\RelationException;
use Plank\Siren\Builders\EntityRelationDiagram\Relation;
use Plank\Siren\Siren;

it('can render Entity Relationship Diagrams', function () {
    $mermaid = Siren::erd('Entity Relationship Diagram')
        ->addEntity($a = new Entity('Entity A'))
        ->addEntity($b = new Entity('Entity B', [
            Attribute::make('string', 'id', 'The identifier of ModelB', Key::PRIMARY),
            Attribute::make('int', 'age'),
        ]))
        ->addEntity($c = new Entity('Entity C', [Attribute::make('place', 'city')]))
        ->addEntity($d = new Entity('Entity D', [Attribute::make('coordinates', 'latlng')]))
        ->addEntity($e = new Entity('Entity E'))
        ->addRelation(Relation::make('A to B', $a, $b)
            ->cardinality($a, Cardinality::ZERO_OR_ONE)
            ->cardinality($b, Cardinality::ZERO_OR_ONE))
        ->addRelation(Relation::make('A to C', $a, $c)
            ->cardinality($a, Cardinality::ZERO_OR_MORE)
            ->cardinality($c, Cardinality::ZERO_OR_MORE))
        ->addRelation(Relation::make('A to D', $a, $d)
            ->cardinality($a, Cardinality::ONE_OR_MORE)
            ->cardinality($d, Cardinality::ONE_OR_MORE)
            ->identifying())
        ->addRelation(Relation::make('A to E', $a, $e)->nonIdentifying());

    expect((string) $mermaid)->toBe("---\ntitle: Entity Relationship Diagram\n---\nerDiagram\n\"Entity A\"\n\"Entity B\" {\nstring id PK \"The identifier of ModelB\"\nint age\n}\n\"Entity C\" {\nplace city\n}\n\"Entity D\" {\ncoordinates latlng\n}\n\"Entity E\"\n\"Entity A\" |o--o| \"Entity B\" : \"A to B\"\n\"Entity A\" }o--o{ \"Entity C\" : \"A to C\"\n\"Entity A\" }|--|{ \"Entity D\" : \"A to D\"\n\"Entity A\" ||..|| \"Entity E\" : \"A to E\"\n");
});

it('does not allow you to create multiple entities with the same name', function () {
    expect(function () {
        Siren::erd()
            ->addEntity(new Entity('EntityA'))
            ->addEntity(new Entity('EntityA'));
    })->toThrow(EntityException::class);
});

it('does not allow you to create multiple entity relations with the same key', function () {
    expect(function () {
        Siren::erd()
            ->addEntity($a = new Entity('Entity A'))
            ->addEntity($b = new Entity('Entity B'))
            ->addRelation(Relation::make('A to B', $a, $b))
            ->addRelation(Relation::make('A to B', $a, $b));
    })->toThrow(RelationException::class);
});

it('it allows you to remove classes from the er diagram', function () {
    $mermaid = Siren::erd()
        ->addEntity($a = new Entity('Entity A'))
        ->addEntity(new Entity('Entity B'))
        ->removeEntity($a);

    expect((string) $mermaid)->not()->toContain('Entity A');
});

it('it allows you to remove relations from the er diagram', function () {
    $mermaid = Siren::erd()
        ->addEntity($a = new Entity('Entity A'))
        ->addEntity($b = new Entity('Entity B'))
        ->addEntity($c = new Entity('Entity C'))
        ->addRelation(Relation::make('A to B', $a, $b))
        ->addRelation(Relation::make('B to C', $b, $c))
        ->removeRelation(Relation::make('A to B', $a, $b));

    expect((string) $mermaid)->not()->toContain('"Entity A" ||--|| "Entity B"');
});
