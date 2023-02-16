<?php

namespace Plank\Siren\Builders\Flowchart\Enums;

enum Arrow: string
{
    case NONE = '';
    case ARROW = '>';
    case DOT = 'o';
    case X = 'x';

    public function left()
    {
        if ($this->value === '>') {
            return '<';
        }

        return $this->value;
    }

    public function right()
    {
        return $this->value;
    }
}
