<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Classifier;
use Plank\Siren\Builders\ClassDiagram\Enums\Visibility;

class Member
{
    public function __construct(
        protected readonly string $name,
        protected readonly ?Type $type = null,
        protected readonly ?Classifier $classifier = null,
        protected readonly ?Visibility $visibility = null
    ) {
    }

    public static function make(
        string $name,
        ?Type $type = null,
        ?Classifier $classifier = null,
        ?Visibility $visibility = null
    ): self {
        return new self($name, $type, $classifier, $visibility);
    }

    public function type(string $type, ?string $of = null): self
    {
        return new self(
            $this->name,
            new Type($type, $of),
            $this->classifier,
            $this->visibility
        );
    }

    public function abstract(): self
    {
        return new self(
            $this->name,
            $this->type,
            Classifier::ABSTRACT,
            $this->visibility
        );
    }

    public function static(): self
    {
        return new self(
            $this->name,
            $this->type,
            Classifier::STATIC,
            $this->visibility
        );
    }

    public function public(): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->classifier,
            Visibility::PUBLIC
        );
    }

    public function protected(): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->classifier,
            Visibility::PROTECTED
        );
    }

    public function private(): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->classifier,
            Visibility::PRIVATE
        );
    }

    public function internal(): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->classifier,
            Visibility::INTERNAL
        );
    }

    public function __toString(): string
    {
        $md = $this->visibility ? $this->visibility->value : '';

        if ($this->type) {
            $md = $this->type.' ';
        }

        $md .= $this->name;

        if ($this->classifier) {
            $md .= $this->classifier->value;
        }

        return $md;
    }
}
