<?php

namespace Plank\Siren\Builders\EntityRelationDiagram\Enums;

enum Cardinality: string
{
    case ZERO_OR_ONE = '|o';
    case ZERO_OR_MORE = '}o';
    case ONE_OR_MORE = '}|';
    case ONLY_ONE = '||';

    public function left(): string
    {
        return $this->value;
    }

    public function right(): string
    {
        switch ($this->value) {
            case '|o':
                return 'o|';

            case '}o':
                return 'o{';

            case '}|':
                return '|{';
        }

        return $this->value;
    }
}
