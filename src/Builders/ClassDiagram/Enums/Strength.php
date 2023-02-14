<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Strength: string
{
    case DEPENDENCY = '..';
    case ASSOCIATION = '--';
}
