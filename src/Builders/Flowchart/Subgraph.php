<?php

namespace Plank\Siren\Builders\Flowchart;

use Plank\Siren\Builders\Flowchart\Enums\Direction;
use Plank\Siren\Builders\Flowchart\Exceptions\IdentifierException;
use Plank\Siren\Traits\HasIdentifier;

class Subgraph extends Flowchart
{
    use HasIdentifier;

    /**
     * @param  array<Flowchart>  $subgraphs
     * @param  array<Node>  $nodes
     * @param  array<Link>  $links
     */
    public function __construct(
        public string $id,
        protected ?Flowchart $parent = null,
        public ?string $title = null
    ) {
        if ($id && ($chars = $this->illegalCharacters($id))) {
            throw new IdentifierException("Illegal Characters in Subgraph id '$id'. [Illegal: $chars]");
        }

        parent::__construct($title);
    }

    public static function make(string $id): self
    {
        return new self($id);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setParent(Flowchart $flowchart)
    {
        $this->parent = $flowchart;

        return $this;
    }

    public function __toString(): string
    {
        $md = $this->indentation().'subgraph '.$this->id;

        if ($this->title) {
            $md .= ' ['.$this->escape($this->title).']';
        }

        $md .= "\n";

        if ($this->direction !== Direction::TOP_DOWN) {
            $md .= $this->indentation().'  direction '.$this->direction->value."\n";
        }

        $md .= implode('', $this->nodes);
        $md .= implode('', $this->subgraphs);
        $md .= implode('', $this->links);

        $md .= $this->indentation()."end\n";

        return $md;
    }
}
