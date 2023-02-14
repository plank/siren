<?php

namespace Plank\Siren;

use Plank\Siren\Builders\EntityRelationDiagram\EntityRelationDiagram;

class Siren
{

    public static function erd(string $title = null): EntityRelationDiagram
    {
        return new EntityRelationDiagram($title);
    }
}
