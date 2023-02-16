<?php

namespace Plank\Siren\Traits;

trait HasIdentifier
{
    protected function illegalCharacters(string $id): string
    {
        $matches = [];

        preg_match_all('/[\s<>\%\(\)\[\]{}^~;]/i', $id, $matches);

        return $matches ? implode(', ', $matches[0]) : '';
    }
}
