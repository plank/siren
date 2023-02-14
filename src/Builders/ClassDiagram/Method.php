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

    public static function make(
        string $name,
        array $arguments = [],
        ?Type $return = null,
        ?Visibility $visibility = null,
        ?Classifier $classifier = null
    ): self {
        return new self($name, $arguments, $return, $visibility, $classifier);
    }

    public function addArgument(string $name, ?string $type = null, ?string $of = null)
    {
        $argType = null;

        if ($type && $of) {
            $argType = Type::generic($type, $of);
        } elseif ($type) {
            $argType = Type::make($type);
        }

        return new self(
            $this->name,
            [...$this->arguments, new Argument($name, $argType)],
            $this->return,
            $this->visibility,
            $this->classifier
        );
    }

    public function return(string $type, ?string $of = null): self
    {
        return new self(
            $this->name,
            $this->arguments,
            new Type($type, $of),
            $this->visibility,
            $this->classifier
        );
    }

    public function abstract(): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            $this->visibility,
            Classifier::ABSTRACT
        );
    }

    public function static(): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            $this->visibility,
            Classifier::STATIC
        );
    }

    public function public(): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            Visibility::PUBLIC,
            $this->classifier
        );
    }

    public function protected(): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            Visibility::PROTECTED,
            $this->classifier
        );
    }

    public function private(): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            Visibility::PRIVATE,
            $this->classifier
        );
    }

    public function internal(): self
    {
        return new self(
            $this->name,
            $this->arguments,
            $this->return,
            Visibility::INTERNAL,
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
