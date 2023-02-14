<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Annotation: string
{
    case INTERFACE = 'interface';
    case ABSTRACT = 'abstract';
    case SERVICE = 'service';
    case ENUMERATION = 'enumeration';
}
