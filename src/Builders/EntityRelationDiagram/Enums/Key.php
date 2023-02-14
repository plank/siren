<?php

namespace Plank\Siren\Builders\EntityRelationDiagram\Enums;

enum Key: string
{
    case PRIMARY = 'PK';
    case FOREIGN = 'FK';
}
