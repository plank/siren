<?php

namespace Plank\Siren;

use Plank\Siren\Builders\ClassDiagram\ClassDiagram;
use Plank\Siren\Builders\EntityRelationDiagram\EntityRelationDiagram;

class Siren
{
    public static function class(string $title = null): ClassDiagram
    {
        return new ClassDiagram($title);
    }

    public static function erd(string $title = null): EntityRelationDiagram
    {
        return new EntityRelationDiagram($title);
    }
}
