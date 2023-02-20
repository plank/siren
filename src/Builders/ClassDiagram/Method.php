<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Classifier;
use Plank\Siren\Builders\ClassDiagram\Enums\Visibility;

class Method
{
    /**
     * @param  array<Argument>  $arguments
     */
    public function __construct(
        protected readonly string $name,
        protected readonly array $arguments = [],
        protected readonly ?Type $return = null,
        protected readonly ?Visibility $visibility = null,
        protected readonly ?Classifier $classifier = null
    ) {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function addArgument(string $name, ?string $type = null): self
    {
        return new self(
            $this->name,
            [...$this->arguments, Argument::make($name, $type)],
            $this->return,
            $this->visibility,
            $this->classifier
        );
    }

    public function return(string $type): self
    {
        return new self(
            $this->name,
            $this->arguments,
            Type::make($type),
            $this->visibility,
            $this->classifier
        );
    }

    public function classifier(Classifier $classifier): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            $this->visibility,
            $classifier
        );
    }

    public function visibility(Visibility $visibility): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            $visibility,
            $this->classifier
        );
    }

    public function __toString(): string
    {
        $md = $this->visibility ? $this->visibility->value : '';

        $md .= $this->name.'(';

        $numArgs = count($this->arguments);

        foreach ($this->arguments as $idx => $argument) {
            $md .= $argument;

            if ($idx !== $numArgs - 1) {
                $md .= ', ';
            }
        }

        $md .= ')';

        if ($this->classifier) {
            $md .= $this->classifier->value;
        }

        if ($this->return) {
            $md .= ' '.$this->return;
        }

        return $md;
    }
}
