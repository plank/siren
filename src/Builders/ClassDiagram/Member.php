<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Classifier;
use Plank\Siren\Builders\ClassDiagram\Enums\Visibility;
use Plank\Siren\Builders\ClassDiagram\Exceptions\MemberException;

class Member
{
    public function __construct(
        protected readonly string $name,
        protected readonly ?Type $type = null,
        protected readonly ?Classifier $classifier = null,
        protected readonly ?Visibility $visibility = null
    ) {
    }

    public static function make(string $name, ?string $type = null): self
    {
        return new self($name, $type ? Type::make($type) : null);
    }

    public function type(string $type): self
    {
        return new self(
            $this->name,
            Type::make($type),
            $this->classifier,
            $this->visibility
        );
    }

    public function classifier(Classifier $classifier): self
    {
        if ($classifier === Classifier::ABSTRACT) {
            throw new MemberException('You cannot declare members abstract.');
        }

        return new self(
            $this->name,
            $this->type,
            $classifier,
            $this->visibility
        );
    }

    public function visibility(Visibility $visibility): self
    {
        return new self(
            $this->name,
            $this->type,
            $this->classifier,
            $visibility
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
