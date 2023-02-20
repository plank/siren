<?php

use Plank\Siren\Builders\EntityRelationDiagram\Attribute;
use Plank\Siren\Builders\EntityRelationDiagram\Entity;
use Plank\Siren\Builders\EntityRelationDiagram\Enums\Cardinality;
use Plank\Siren\Builders\EntityRelationDiagram\Enums\Identification;
use Plank\Siren\Builders\EntityRelationDiagram\Enums\Key;
use Plank\Siren\Builders\EntityRelationDiagram\Exceptions\EntityException;
use Plank\Siren\Builders\EntityRelationDiagram\Exceptions\RelationException;
use Plank\Siren\Builders\EntityRelationDiagram\Relation;
use Plank\Siren\Siren;

it('can render Entity Relationship Diagrams', function () {
    $diagram = Siren::erd('Entity Relationship Diagram')
        ->addEntity($a = Entity::make('Entity A'))
        ->addEntity($b = Entity::make('Entity B')
            ->addAttribute(Attribute::make('id', 'string')
                ->comment('The identifier of ModelB')
                ->key(Key::PRIMARY)
            )->addAttribute(Attribute::make('age', 'int'))
        )
        ->addEntity($c = Entity::make('Entity C')
            ->addAttribute(Attribute::make('city', 'place'))
        )->addEntity($d = Entity::make('Entity D')
            ->addAttribute(Attribute::make('latlng', 'coordinates'))
        )->addEntity($e = Entity::make('Entity E')
        )->addRelation(Relation::make('A to B', $a, $b)
            ->cardinality($a, Cardinality::ZERO_OR_ONE)
            ->cardinality($b, Cardinality::ZERO_OR_ONE)
        )->addRelation(Relation::make('A to C', $a, $c)
            ->cardinality($a, Cardinality::ZERO_OR_MORE)
            ->cardinality($c, Cardinality::ZERO_OR_MORE)
        )->addRelation(Relation::make('A to D', $a, $d)
            ->cardinality($a, Cardinality::ONE_OR_MORE)
            ->cardinality($d, Cardinality::ONE_OR_MORE)
            ->identification(Identification::IDENTIFYING)
        )->addRelation(Relation::make('A to E', $a, $e)
            ->identification(Identification::NON_IDENTIFYING)
        );

    expect((string) $diagram)->toBe("---\ntitle: Entity Relationship Diagram\n---\nerDiagram\n\"Entity A\"\n\"Entity B\" {\nstring id PK \"The identifier of ModelB\"\nint age\n}\n\"Entity C\" {\nplace city\n}\n\"Entity D\" {\ncoordinates latlng\n}\n\"Entity E\"\n\"Entity A\" |o--o| \"Entity B\" : \"A to B\"\n\"Entity A\" }o--o{ \"Entity C\" : \"A to C\"\n\"Entity A\" }|--|{ \"Entity D\" : \"A to D\"\n\"Entity A\" ||..|| \"Entity E\" : \"A to E\"\n");
});

it('does not allow you to create multiple entities with the same name', function () {
    expect(function () {
        Siren::erd()
            ->addEntity(Entity::make('EntityA'))
            ->addEntity(Entity::make('EntityA'));
    })->toThrow(EntityException::class);
});

it('does not allow you to create multiple entity relations with the same key', function () {
    expect(function () {
        Siren::erd()
            ->addEntity($a = Entity::make('Entity A'))
            ->addEntity($b = Entity::make('Entity B'))
            ->addRelation(Relation::make('A to B', $a, $b))
            ->addRelation(Relation::make('A to B', $a, $b));
    })->toThrow(RelationException::class);
});

it('allows you to remove classes from the er diagram', function () {
    $diagram = Siren::erd()
        ->addEntity($a = Entity::make('Entity A'))
        ->addEntity(Entity::make('Entity B'))
        ->removeEntity($a);

    expect((string) $diagram)->not()->toContain('Entity A');
});

it('allows you to remove relations from the er diagram', function () {
    $diagram = Siren::erd()
        ->addEntity($a = Entity::make('Entity A'))
        ->addEntity($b = Entity::make('Entity B'))
        ->addEntity($c = Entity::make('Entity C'))
        ->addRelation(Relation::make('A to B', $a, $b))
        ->addRelation(Relation::make('B to C', $b, $c))
        ->removeRelation(Relation::make('A to B', $a, $b));

    expect((string) $diagram)->not()->toContain('"Entity A" ||--|| "Entity B"');

    var_dump(
        (string) Siren::erd()
        ->addEntity($a = Entity::make('Entity A'))
        ->addEntity($b = Entity::make('Entity B'))
        ->addEntity($c = Entity::make('Entity C'))
        ->addRelation(Relation::make('A to B', $a, $b))
        ->addRelation(Relation::make('B to C', $a, $b)->identification(Identification::IDENTIFYING))
        ->addRelation(Relation::make('C to A', $c, $a)->identification(Identification::NON_IDENTIFYING)));
});
