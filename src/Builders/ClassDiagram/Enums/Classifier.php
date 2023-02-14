<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Classifier: string
{
    case ABSTRACT = '*';
    case STATIC = '$';
}
