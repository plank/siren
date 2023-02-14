<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Annotation;
use Plank\Siren\Builders\ClassDiagram\Exceptions\ClassException;

class UmlClass
{
    /**
     * @param  array<Member>  $members
     * @param  array<Method>  $methods
     */
    public function __construct(
        public readonly string $name,
        public readonly array $members = [],
        public readonly array $methods = [],
        public readonly ?Annotation $annotation = null
    ) {
        if (preg_match('/\s+/', $name)) {
            throw new ClassException('Class names may not contain spaces.');
        }
    }

    public function interface(): self
    {
        return new self(
            $this->name,
            $this->members,
            $this->methods,
            Annotation::INTERFACE
        );
    }

    public function abstract(): self
    {
        return new self(
            $this->name,
            $this->members,
            $this->methods,
            Annotation::ABSTRACT
        );
    }

    public function service(): self
    {
        return new self(
            $this->name,
            $this->members,
            $this->methods,
            Annotation::SERVICE
        );
    }

    public function enumeration(): self
    {
        return new self(
            $this->name,
            $this->members,
            $this->methods,
            Annotation::ENUMERATION
        );
    }

    public function __toString(): string
    {
        $md = "class $this->name";

        if (empty($this->members) && empty($this->methods) && $this->annotation === null) {
            return $md;
        }

        $md .= "{\n";

        if ($this->annotation) {
            $md .= '<<'.$this->annotation->value.">>\n";
        }

        foreach ($this->members as $member) {
            $md .= $member."\n";
        }

        foreach ($this->methods as $method) {
            $md .= $method."\n";
        }

        $md .= "}\n";

        return $md;
    }
}
