<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Multiplicity: string
{
    case ONLY_ONE = '1';
    case ZERO_OR_ONE = '0..1';
    case ONE_OR_MORE = '1..*';
    case MANY = '*';
    case N = 'n';
    case ZERO_TO_N = '0..n';
    case ONE_TO_N = '1..n';
}
