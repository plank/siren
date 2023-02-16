<?php

namespace Plank\Siren\Builders\Flowchart\Enums;

enum Line: string
{
    case SOLID = '---';
    case DOTTED = '-.-';
    case THICK = '===';

    public function withArrows(Arrow $src, Arrow $dest, int $min)
    {
        $cap = $this->value[0];
        $repeater = $this->value[1];

        if ($dest !== Arrow::NONE && $cap === $repeater) {
            $min -= 1;
        }

        $line = $cap.str_repeat($repeater, $min + 1).$cap;

        return $src->left().$line.$dest->right();
    }
}
