<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Visibility: string
{
    case PUBLIC = '+';
    case PROTECTED = '#';
    case PRIVATE = '-';
    case INTERNAL = '~';
}
