<?php

namespace Plank\Siren;

use Plank\Siren\Builders\ClassDiagram\ClassDiagram;
use Plank\Siren\Builders\EntityRelationDiagram\EntityRelationDiagram;
use Plank\Siren\Builders\Flowchart\Flowchart;

class Siren
{
    public static function graph(string $title = null): Flowchart
    {
        return self::flowchart($title);
    }

    public static function flowchart(string $title = null): Flowchart
    {
        return new Flowchart($title);
    }

    public static function class(string $title = null): ClassDiagram
    {
        return new ClassDiagram($title);
    }

    public static function erd(string $title = null): EntityRelationDiagram
    {
        return new EntityRelationDiagram($title);
    }
}
