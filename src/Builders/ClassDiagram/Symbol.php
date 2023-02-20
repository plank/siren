<?php

namespace Plank\Siren\Builders\ClassDiagram;

use Plank\Siren\Builders\ClassDiagram\Enums\Modifier;
use Plank\Siren\Builders\ClassDiagram\Exceptions\SymbolException;

class Symbol
{
    /**
     * @param  array<Member>  $members
     * @param  array<Method>  $methods
     */
    public function __construct(
        public readonly string $name,
        public readonly array $members = [],
        public readonly array $methods = [],
        public readonly ?string $annotation = null
    ) {
        if (preg_match('/\s+/', $name)) {
            throw new SymbolException('Class names may not contain spaces.');
        }
    }

    public static function class(string $name, ?Modifier $modifier = null): self
    {
        return new self($name, [], [], $modifier ? $modifier->value : null);
    }

    public static function interface(string $name): self
    {
        return new self($name, [], [], 'interface');
    }

    public static function enum(string $name): self
    {
        return new self($name, [], [], 'enum');
    }

    public static function trait(string $name): self
    {
        return new self($name, [], [], 'trait');
    }

    public function annotation(string $annotation): self
    {
        return new self(
            $this->name,
            $this->members,
            $this->methods,
            $annotation
        );
    }

    public function addMember(Member $member): self
    {
        if ($this->annotation === 'interface') {
            throw new SymbolException('Interfaces may not conatain properties in php.');
        }

        return new self(
            $this->name,
            [...$this->members, $member],
            $this->methods,
            $this->annotation,
        );
    }

    public function addMethod(Method $method): self
    {
        return new self(
            $this->name,
            $this->members,
            [...$this->methods, $method],
            $this->annotation,
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
            $md .= '<<'.$this->annotation.">>\n";
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
