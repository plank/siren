<?php

use Plank\Siren\Builders\Flowchart\Enums\Arrow;
use Plank\Siren\Builders\Flowchart\Enums\Direction;
use Plank\Siren\Builders\Flowchart\Enums\Line;
use Plank\Siren\Builders\Flowchart\Enums\Shape;
use Plank\Siren\Builders\Flowchart\Exceptions\IdentifierException;
use Plank\Siren\Builders\Flowchart\Exceptions\LinkException;
use Plank\Siren\Builders\Flowchart\Exceptions\NodeException;
use Plank\Siren\Builders\Flowchart\Exceptions\SubgraphException;
use Plank\Siren\Builders\Flowchart\Flowchart;
use Plank\Siren\Builders\Flowchart\Link;
use Plank\Siren\Builders\Flowchart\Node;
use Plank\Siren\Builders\Flowchart\Subgraph;
use Plank\Siren\Siren;

it('can render flowcharts', function () {
    $sg3 = Subgraph::make('Subgraph3')->direction(Direction::TOP_TO_BOTTOM)
        ->addNode($sg3a = Node::make('sg3A')->shape(Shape::PARALLELOGRAM))
        ->addNode($sg3b = Node::make('sg3B')->shape(Shape::PARALLELOGRAM_ALT))
        ->addLink(Link::make($sg3a, $sg3b)->text('sg3A to sg3B')->arrow(Arrow::DOT))
        ->addLink(Link::make($sg3b, $sg3a)->text('sg3B to sg3A')->multiArrow(Arrow::NONE))
        ->addSubgraph($subsub = Subgraph::make('subsub'));

    $subsub->addNode($subsuba = Node::make('subsubA'))
        ->addNode($subsubb = Node::make('subsubB'))
        ->addNode($subsubc = Node::make('subsubC'))
        ->addLink(Link::make($subsubb, $subsubc))
        ->addLink(Link::make($subsubc, $subsuba));

    $flowchart = Siren::flowchart('Flowchart')
        ->addNode($a = Node::make('A'))
        ->addNode($b = Node::make('B')->text('B Text'))
        ->addNode($c = Node::make('C')->text('C for &cent;s')->shape(Shape::SQUARE))
        ->addNode($d = Node::make('D')->shape(Shape::DOUBLE_CIRCLE))
        ->addSubgraph($sg1 = Subgraph::make('Subgraph1')->direction(Direction::LEFT_TO_RIGHT))
        ->addSubgraph($sg2 = Subgraph::make('Subgraph2')->direction(Direction::RIGHT_TO_LEFT))
        ->addSubgraph($sg3)
        ->addSubgraph($sg4 = Subgraph::make('Subgraph4')->title('Subgraph4 Title')->direction(Direction::BOTTOM_TO_TOP))
        ->addLink(Link::make($a, $b))
        ->addLink(Link::make($b, $c))
        ->addLink(Link::make($c, $d))
        ->addLink(Link::make($d, $a))
        ->addLink(Link::make($a, $sg1))
        ->addLink(Link::make($a, $sg2))
        ->addLink(Link::make($a, $sg3))
        ->addLink(Link::make($a, $sg4))
        ->addLink(Link::make($sg1, $sg2))
        ->addLink(Link::make($sg2, $sg3))
        ->addLink(Link::make($sg3, $sg4))
        ->addLink(Link::make($sg4, $sg1));

    $sg1->addNode($sg1a = Node::make('sg1A')->shape(Shape::ROUND))
        ->addNode($sg1b = Node::make('sg1B')->shape(Shape::STADIUM))
        ->addNode($sg1c = Node::make('sg1C')->shape(Shape::SUBROUTINE))
        ->addNode($sg1d = Node::make('sg1D')->shape(Shape::CYLINDER))
        ->addLink(Link::make($sg1a, $sg1b)->text('sg1A to sg1B'))
        ->addLink(Link::make($sg1b, $sg1a)->text('sg1B to sg1A')->line(Line::DOTTED))
        ->addLink(Link::make($sg1a, $sg1c)->text('sg1A to sg1C')->arrow(Arrow::NONE))
        ->addLink(Link::make($sg1c, $sg1d)->text('sg1C to sg1D')->line(Line::THICK)->arrow(Arrow::ARROW))
        ->addLink(Link::make($sg1d, $sg1b)->text('sg1D to sg1B')->line(Line::SOLID)->arrow(Arrow::X)->span(2));

    $sg2->addNode($sg2a = Node::make('sg2A')->shape(Shape::CIRCLE))
        ->addNode($sg2b = Node::make('sg2B')->shape(Shape::ASYMMETRIC))
        ->addNode($sg2c = Node::make('sg2C')->shape(Shape::RHOMBUS))
        ->addNode($sg2d = Node::make('sg2D')->shape(Shape::HEXAGON))
        ->addLink(Link::make($sg2a, $sg2b)->text('sg2A to sg2B')->arrow(Arrow::DOT))
        ->addLink(Link::make($sg2b, $sg2a)->text('sg2B to sg2A')->multiArrow(Arrow::NONE))
        ->addLink(Link::make($sg2a, $sg2c)->text('sg2A to sg2C')->multiArrow(Arrow::ARROW)->span(2))
        ->addLink(Link::make($sg2c, $sg2d)->text('sg2C to sg2D')->multiArrow(Arrow::DOT))
        ->addLink(Link::make($sg2d, $sg2b)->text('sg2D to sg2B')->multiArrow(Arrow::X));

    $sg4->addNode($sg4a = Node::make('sg4A'))
        ->addNode($sg4b = Node::make('sg4B'))
        ->addNode($sg4c = Node::make('sg4C'))
        ->addLink(Link::make($sg4a, $sg4b)->text('sg4A to sg4B')->arrow(Arrow::NONE))
        ->addLink(Link::make($sg4b, $sg4c)->text('sg4B to sg4C')->arrow(Arrow::NONE))
        ->addLink(Link::make($sg4c, $sg4a)->text('sg4C to sg4A')->arrow(Arrow::NONE)->span(2));

    $flowchart->addLink(Link::make($subsub, $sg1)->line(Line::DOTTED));

    expect((string) $flowchart)->toBe("---\ntitle: Flowchart\n---\nflowchart TD\n  A\n  B[\"B Text\"]\n  C[\"C for &amp;cent;s\"]\n  D(((D)))\n  subgraph Subgraph1\n    direction LR\n    sg1A(sg1A)\n    sg1B([sg1B])\n    sg1C[[sg1C]]\n    sg1D[(sg1D)]\n    sg1A --- |\"sg1A to sg1B\"| sg1B\n    sg1B -.- |\"sg1B to sg1A\"| sg1A\n    sg1A --- |\"sg1A to sg1C\"| sg1C\n    sg1C ==> |\"sg1C to sg1D\"| sg1D\n    sg1D ----x |\"sg1D to sg1B\"| sg1B\n  end\n  subgraph Subgraph2\n    direction RL\n    sg2A((sg2A))\n    sg2B>sg2B]\n    sg2C{sg2C}\n    sg2D{{sg2D}}\n    sg2A --o |\"sg2A to sg2B\"| sg2B\n    sg2B --- |\"sg2B to sg2A\"| sg2A\n    sg2A <----> |\"sg2A to sg2C\"| sg2C\n    sg2C o--o |\"sg2C to sg2D\"| sg2D\n    sg2D x--x |\"sg2D to sg2B\"| sg2B\n  end\n  subgraph Subgraph3\n    direction TB\n    sg3A[/sg3A/]\n    sg3B[\sg3B\]\n    subgraph subsub\n      subsubA\n      subsubB\n      subsubC\n      subsubB --- subsubC\n      subsubC --- subsubA\n    end\n    sg3A --o |\"sg3A to sg3B\"| sg3B\n    sg3B --- |\"sg3B to sg3A\"| sg3A\n  end\n  subgraph Subgraph4 [\"Subgraph4 Title\"]\n    direction BT\n    sg4A\n    sg4B\n    sg4C\n    sg4A --- |\"sg4A to sg4B\"| sg4B\n    sg4B --- |\"sg4B to sg4C\"| sg4C\n    sg4C ----- |\"sg4C to sg4A\"| sg4A\n  end\n  A --- B\n  B --- C\n  C --- D\n  D --- A\n  A --- Subgraph1\n  A --- Subgraph2\n  A --- Subgraph3\n  A --- Subgraph4\n  Subgraph1 --- Subgraph2\n  Subgraph2 --- Subgraph3\n  Subgraph3 --- Subgraph4\n  Subgraph4 --- Subgraph1\n  subsub -.- Subgraph1\n");
});

it('lets you call graph as an alias of flowchart', function () {
    expect(Siren::graph())->toBeInstanceOf(Flowchart::class);
});

it('does not allow you to create Node ids with spaces in them', function () {
    expect(fn () => Node::make('Uh Oh'))->toThrow(IdentifierException::class);
});

it('does not allow you to create Subgraph ids with spaces in them', function () {
    expect(fn () => Subgraph::make('Uh Oh'))->toThrow(IdentifierException::class);
});

it('does not allow you to create nodes with the same id', function () {
    expect(function () {
        Siren::flowchart()
            ->addNode(Node::make('NodeA'))
            ->addNode(Node::make('NodeA'));
    })->toThrow(NodeException::class);
});

it('does not allow you to create multiple links with the same key', function () {
    expect(function () {
        Siren::flowchart()
            ->addNode($a = Node::make('NodeA'))
            ->addNode($b = Node::make('NodeB'))
            ->addLink(Link::make($a, $b))
            ->addLink(Link::make($a, $b));
    })->toThrow(LinkException::class);
});

it('allows you to remove nodes from the flowchart', function () {
    $flowchart = Siren::flowchart()
        ->addNode($a = Node::make('NodeA'))
        ->addNode(Node::make('NodeB'))
        ->removeNode($a);

    expect((string) $flowchart)->not()->toContain('NodeA');
});

it('allows you to remove relations from the class diagram', function () {
    $flowchart = Siren::flowchart()
        ->addNode($a = Node::make('NodeA'))
        ->addNode($b = Node::make('NodeB'))
        ->addNode($c = Node::make('NodeC'))
        ->addLink($linkA = Link::make($a, $b))
        ->addLink(Link::make($b, $c))
        ->removeLink($linkA);

    expect((string) $flowchart)->not()->toContain('NodeA --- NodeB');
});

it('throws an error when two subgraphs share the same id', function () {
    expect(function () {
        Siren::flowchart()
            ->addSubgraph(Subgraph::make('SubgraphA'))
            ->addSubgraph(Subgraph::make('SubgraphA'));
    })->toThrow(SubgraphException::class);
});

it('throws an error when a subgraph collides on nodes', function () {
    expect(function () {
        Siren::flowchart()
            ->addSubgraph(Subgraph::make('SubgraphA')->addNode(Node::make('NodeA')))
            ->addSubgraph(Subgraph::make('NodeCollision')->addNode(Node::make('NodeA')));
    })->toThrow(SubgraphException::class);
});

it('throws an error when a subgraph collides on links', function () {
    expect(function () {
        Siren::flowchart()
            ->addSubgraph(Subgraph::make('SubgraphA')->addLink(Link::make(Node::make('A'), Node::make('B'))))
            ->addSubgraph(Subgraph::make('LinkCollision')->addLink(Link::make(Node::make('A'), Node::make('B'))));
    })->toThrow(SubgraphException::class);
});
