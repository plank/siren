<?php

namespace Plank\Siren\Builders\ClassDiagram\Enums;

enum Direction: string
{
    case TOP_DOWN = 'TD';
    case TOP_TO_BOTTOM = 'TB';
    case BOTTOM_TO_TOP = 'BT';
    case RIGHT_TO_LEFT = 'RL';
    case LEFT_TO_RIGHT = 'LR';
}
