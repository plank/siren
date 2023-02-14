<?php

use Plank\Siren\Builders\ClassDiagram\Enums\Connection;
use Plank\Siren\Builders\ClassDiagram\Enums\Multiplicity;
use Plank\Siren\Builders\ClassDiagram\Exceptions\ClassException;
use Plank\Siren\Builders\ClassDiagram\Exceptions\RelationException;
use Plank\Siren\Builders\ClassDiagram\Member;
use Plank\Siren\Builders\ClassDiagram\Method;
use Plank\Siren\Builders\ClassDiagram\Relation;
use Plank\Siren\Builders\ClassDiagram\UmlClass;
use Plank\Siren\Siren;

it('can render class diagrams', function () {
    $mermaid = Siren::class('Class Diagram')
        ->addClass($a = new UmlClass('ClassA'))
        ->addClass($b = (new UmlClass('ClassB'))->interface())
        ->addClass($c = (new UmlClass('ClassC'))->abstract())
        ->addClass($d = (new UmlClass('ClassD'))->service())
        ->addClass($e = (new UmlClass('ClassE'))->enumeration())
        ->addClass($f = new UmlClass('ClassF'))
        ->addClass($g = new UmlClass('ClassG'))
        ->addClass($h = new UmlClass('ClassH'))
        ->addClass($i = new UmlClass('ClassI'))
        ->addClass($j = new UmlClass('ClassJ', [
            Member::make('one'),
            Member::make('two')->public(),
            Member::make('three')->protected(),
            Member::make('four')->private()->abstract()->type('int'),
            Member::make('five')->internal()->static()->type('List', 'Number'),
        ], [
            Method::make('a')
                ->addArgument('first')
                ->addArgument('second', 'string')
                ->addArgument('third', 'array', 'int'),
            Method::make('b')->public()->abstract(),
            Method::make('c')->protected()->static(),
            Method::make('d')->private()->return('bool'),
            Method::make('e')->internal()->return('array', 'int'),
        ]))
        ->addRelation(Relation::make($a, $b)
            ->connection($a, Connection::AGGREGATION)
            ->multiplicity($b, Multiplicity::MANY))
        ->addRelation(Relation::make($a, $c)
            ->name('A to C')
            ->connection($a, Connection::COMPOSITION)
            ->multiplicity($c, Multiplicity::N)
            ->dependency())
        ->addRelation(Relation::make($a, $d)
            ->name('A to D')
            ->connection($a, Connection::DEPENDENCY)
            ->multiplicity($d, Multiplicity::ONE_OR_MORE)
            ->association())
        ->addRelation(Relation::make($a, $e)
            ->name('A to E')
            ->connection($a, Connection::EXTENSION)
            ->multiplicity($e, Multiplicity::ONE_TO_N))
        ->addRelation(Relation::make($a, $f)
            ->name('A to F')
            ->multiplicity($a, Multiplicity::ONLY_ONE)
            ->connection($f, Connection::AGGREGATION))
        ->addRelation(Relation::make($a, $g)
            ->name('A to G')
            ->multiplicity($a, Multiplicity::ZERO_OR_ONE)
            ->connection($g, Connection::COMPOSITION))
        ->addRelation(Relation::make($a, $h)
            ->name('A to H')
            ->multiplicity($a, Multiplicity::ZERO_TO_N)
            ->connection($h, Connection::DEPENDENCY))
        ->addRelation(Relation::make($a, $i)
            ->connection($i, Connection::EXTENSION)
            ->name('A to I'))
        ->addRelation(Relation::make($a, $j)->dependency());

    expect((string) $mermaid)->toContain("---\ntitle: Class Diagram\n---\nclassDiagram\nclass ClassA\nclass ClassB{\n<<interface>>\n}\n\nclass ClassC{\n<<abstract>>\n}\n\nclass ClassD{\n<<service>>\n}\n\nclass ClassE{\n<<enumeration>>\n}\n\nclass ClassF\nclass ClassG\nclass ClassH\nclass ClassI\nclass ClassJ{\none\n+two\n#three\nint four*\nList~Number~ five$\na(first, string second, array~int~ third)\n+b()*\n#c()$\n-d() bool\n~e() array~int~\n}\n\nClassA o--\"*\" ClassB\nClassA *..\"n\" ClassC : \"A to C\"\nClassA <--\"1..*\" ClassD : \"A to D\"\nClassA <|--\"1..n\" ClassE : \"A to E\"\nClassA \"1\" --o ClassF : \"A to F\"\nClassA \"0..1\" --* ClassG : \"A to G\"\nClassA \"0..n\" --> ClassH : \"A to H\"\nClassA --|> ClassI : \"A to I\"\nClassA .. ClassJ");
});

it('does not allow you to create class names with spaces in them', function () {
    expect(fn () => new UmlClass('Uh Oh'))->toThrow(ClassException::class);
});

it('does not allow you to create multiple classes with the same name', function () {
    expect(function () {
        Siren::class()
            ->addClass(new UmlClass('ClassA'))
            ->addClass(new UmlClass('ClassA'));
    })->toThrow(ClassException::class);
});

it('does not allow you to create multiple class relations with the same key', function () {
    expect(function () {
        Siren::class()
            ->addClass($a = new UmlClass('ClassA'))
            ->addClass($b = new UmlClass('ClassB'))
            ->addRelation(Relation::make($a, $b))
            ->addRelation(Relation::make($a, $b));
    })->toThrow(RelationException::class);
});

it('it allows you to remove classes from the class diagram', function () {
    $mermaid = Siren::class()
        ->addClass($a = new UmlClass('ClassA'))
        ->addClass($b = new UmlClass('ClassB'))
        ->removeClass($a);

    expect((string) $mermaid)->not()->toContain('ClassA');
});

it('it allows you to remove relations from the class diagram', function () {
    $mermaid = Siren::class()
        ->addClass($a = new UmlClass('ClassA'))
        ->addClass($b = new UmlClass('ClassB'))
        ->addClass($c = new UmlClass('ClassC'))
        ->addRelation(Relation::make($a, $b))
        ->addRelation(Relation::make($b, $c))
        ->removeRelation(Relation::make($a, $b));

    expect((string) $mermaid)->not()->toContain('ClassA -- ClassB');
});
