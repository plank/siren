<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Connection: string
{
    case EXTENSION = '<|';
    case DEPENDENCY = '<';
    case COMPOSITION = '*';
    case AGGREGATION = 'o';

    public function left(): string
    {
        return $this->value;
    }

    public function right(): string
    {
        switch ($this->value) {
            case '<|':
                return '|>';

            case '<':
                return '>';
        }

        return $this->value;
    }
}
