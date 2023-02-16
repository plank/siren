<?php

namespace Plank\Siren\Builders\Flowchart;

use Plank\Siren\Builders\Flowchart\Enums\Direction;
use Plank\Siren\Builders\Flowchart\Exceptions\LinkException;
use Plank\Siren\Builders\Flowchart\Exceptions\NodeException;
use Plank\Siren\Builders\Flowchart\Exceptions\SubgraphException;
use Plank\Siren\Traits\Escaped;
use Plank\Siren\Traits\Indented;

class Flowchart
{
    use Indented;
    use Escaped;

    /**
     * @param  array<Flowchart>  $subgraphs
     * @param  array<Node>  $nodes
     * @param  array<Link>  $links
     */
    public function __construct(
        public ?string $title = null,
        protected Direction $direction = Direction::TOP_DOWN,
        protected array $subgraphs = [],
        protected array $nodes = [],
        protected array $links = [],
    ) {
    }

    public function direction(Direction $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function addNode(Node $node): self
    {
        if ($this->hasNode($node)) {
            throw new NodeException('You cannot have duplicate Nodes in the same Flowchart.');
        }

        $node->indent($this->level + 1);
        $this->nodes[$node->id] = $node;

        return $this;
    }

    public function hasNode(Node $node): bool
    {
        foreach ($this->root()->elements() as $element) {
            if ($element->id === $node->id) {
                return true;
            }
        }
        
        return false;
    }

    public function removeNode(Node $node): self
    {
        unset($this->nodes[$node->id]);

        return $this;
    }

    public function addLink(Link $link): self
    {
        if ($this->hasLink($link)) {
            throw new LinkException('You cannot have duplicate Links in the same Flowchart.');
        }

        $link->indent($this->level + 1);
        $this->links[$link->key()] = $link;

        return $this;
    }

    public function hasLink(Link $link): bool
    {
        foreach ($this->root()->links() as $existing) {
            if ($existing->key() === $link->key()) {
                return true;
            }
        }
        
        return false;
    }

    public function removeLink(Link $link): self
    {
        unset($this->links[$link->key()]);

        return $this;
    }

    public function addSubgraph(Subgraph $subgraph)
    {
        if ($this->hasSubgraph($subgraph)) {
            throw new SubgraphException('You cannot have duplicate Subgraphs in the same Flowchart.');
        }

        $subgraph->setParent($this);
        $subgraph->indent($this->level + 1);
        
        $this->subgraphs[$subgraph->id] = $subgraph;

        $subgraph->validate();

        return $this;
    }

    public function hasSubgraph(Subgraph $subgraph): bool
    {
        foreach ($this->root()->elements() as $element) {
            if ($element->id === $subgraph->id) {
                return true;
            }
        }
        
        return false;
    }

    public function validate()
    {
        $root = $this->root();

        $root->validateElements();
        $root->validateLinks();
    }

    public function root(): Flowchart|Subgraph
    {
        $root = $this;

        while ($root instanceof Subgraph && $root->parent !== null) {
            $root = $root->parent;
        }

        return $root;
    }

    public function validateElements()
    {
        if ($duplicates = $this->duplicates($this->elements(), fn (Node|Subgraph $node) => $node->id)) {
            throw new SubgraphException("The following Nodes collide in the graphs. [".implode(', ', $duplicates).']');
        }
    }

    public function validateLinks()
    {
        if ($duplicates = $this->duplicates($this->links(), fn (Link $link) => trim((string) $link))) {
            throw new SubgraphException("The following Links collide in the graphs. [".implode(', ', $duplicates).']');
        }
    }

    protected function duplicates(array $items, callable $identifier): array
    {   
        $mapped = array_map($identifier, $items);
        sort($mapped);

        $duplicate = [];

        for ($i = 0; $i < count($mapped) - 1; $i++) {
            if ($mapped[$i] === $mapped[$i + 1]) {
                $duplicate[$mapped[$i]] = true;
            }
        }

        return array_keys($duplicate);
    }

    /**
     * @return array<Node|Subgraph>
     */
    public function elements(): array
    {
        $nodes = $this->nodes;

        if ($this instanceof Subgraph) {
            $nodes[$this->id] = $this;
        }

        foreach ($this->subgraphs as $subgraph) {
            $nodes = array_merge($nodes, $subgraph->elements());
        }

        return array_values($nodes);
    }

    /**
     * @return array<Link>
     */
    public function links(): array
    {
        $links = $this->links;

        foreach ($this->subgraphs as $subgraph) {
            $links = array_merge($links, $subgraph->links());
        }

        return array_values($links);
    }

    public function indent(int $level): void
    {
        $this->level = $level;

        foreach ($this->subgraphs as $subgraph) {
            $subgraph->indent($level + 1);
        }

        foreach ($this->nodes as $node) {
            $node->indent($level + 1);
        }

        foreach ($this->links as $link) {
            $link->indent($level + 1);
        }
    }

    public function __toString(): string
    {
        $md = '';

        if ($this->title) {
            $md .= "---\n";
            $md .= "title: $this->title\n";
            $md .= "---\n";
        }

        $direction = $this->direction->value;
        $md .= "flowchart $direction\n";

        $md .= implode('', $this->nodes);
        $md .= implode('', $this->subgraphs);
        $md .= implode('', $this->links);

        return $md;
    }
}
