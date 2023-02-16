<?php

namespace Plank\Siren\Traits;

trait Indented
{
    protected int $level = 0;

    public function indent(int $level): void
    {
        $this->level = $level;
    }

    public function indentation(): string
    {
        return str_repeat(' ', $this->level * 2);
    }
}
