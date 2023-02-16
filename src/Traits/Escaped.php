<?php

namespace Plank\Siren\Traits;

trait Escaped
{
    protected function escape(string $text): string
    {
        return '"'.htmlentities($text, ENT_COMPAT).'"';
    }
}
