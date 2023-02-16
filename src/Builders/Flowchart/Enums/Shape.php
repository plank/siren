<?php

namespace Plank\Siren\Builders\Flowchart\Enums;

enum Shape: string
{
    case SQUARE = '[%]';
    case ROUND = '(%)';
    case STADIUM = '([%])';
    case SUBROUTINE = '[[%]]';
    case CYLINDER = '[(%)]';
    case CIRCLE = '((%))';
    case ASYMMETRIC = '>%]';
    case RHOMBUS = '{%}';
    case HEXAGON = '{{%}}';
    case PARALLELOGRAM = '[/%/]';
    case PARALLELOGRAM_ALT = '[\\%\\]';
    case TRAPEZOID = '[/%\]';
    case TRAPEZOID_ALT = '[\%/]';
    case DOUBLE_CIRCLE = '(((%)))';

    public function fill(string $text)
    {
        return str_replace('%', $text, $this->value);
    }
}
