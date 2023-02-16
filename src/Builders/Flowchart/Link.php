<?php

namespace Plank\Siren\Builders\Flowchart;

use Plank\Siren\Builders\Flowchart\Enums\Arrow;
use Plank\Siren\Builders\Flowchart\Enums\Line;
use Plank\Siren\Traits\Escaped;
use Plank\Siren\Traits\Indented;

class Link
{
    use Indented;
    use Escaped;

    public function __construct(
        public readonly Node|Subgraph $src,
        public readonly Node|Subgraph $dest,
        public readonly Arrow $srcArrow = Arrow::NONE,
        public readonly Line $line = Line::SOLID,
        public readonly Arrow $destArrow = Arrow::NONE,
        public readonly ?string $text = null,
        public readonly int $minimumLength = 0
    ) {
    }

    public function key(): string
    {
        return $this->src->id.$this->srcArrow->value.$this->text.$this->line->value.$this->destArrow->value.$this->dest->id;
    }

    public static function make(Node|Subgraph $src, Node|Subgraph $dest): self
    {
        return new self($src, $dest);
    }

    public function text(string $text): self
    {
        return new self(
            $this->src,
            $this->dest,
            $this->srcArrow,
            $this->line,
            $this->destArrow,
            $text,
            $this->minimumLength
        );
    }

    public function solid(): self
    {
        return new self(
            $this->src,
            $this->dest,
            $this->srcArrow,
            Line::SOLID,
            $this->destArrow,
            $this->text,
            $this->minimumLength
        );
    }

    public function dotted(): self
    {
        return new self(
            $this->src,
            $this->dest,
            $this->srcArrow,
            Line::DOTTED,
            $this->destArrow,
            $this->text,
            $this->minimumLength
        );
    }

    public function thick(): self
    {
        return new self(
            $this->src,
            $this->dest,
            $this->srcArrow,
            Line::THICK,
            $this->destArrow,
            $this->text,
            $this->minimumLength
        );
    }

    public function arrow(Arrow $arrow): self
    {
        return new self(
            $this->src,
            $this->dest,
            $this->srcArrow,
            $this->line,
            $arrow,
            $this->text,
            $this->minimumLength
        );
    }

    public function multiArrow(Arrow $arrow): self
    {
        return new self(
            $this->src,
            $this->dest,
            $arrow,
            $this->line,
            $arrow,
            $this->text,
            $this->minimumLength
        );
    }

    public function minimumLength(int $length): self
    {
        return new self(
            $this->src,
            $this->dest,
            $this->srcArrow,
            $this->line,
            $this->destArrow,
            $this->text,
            $length
        );
    }

    public function __toString()
    {
        $md = $this->indentation().$this->src->id.' ';

        $md .= $this->line->withArrows($this->srcArrow, $this->destArrow, $this->minimumLength).' ';

        if ($this->text) {
            $md .= '|'.$this->escape($this->text).'| ';
        }

        $md .= $this->dest->id;

        return $md."\n";
    }
}
