<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Closure;

interface AttributeInterface
{
    public function withName(string $name): AttributeInterface;
    public function setWithoutValue(bool $withoutValue): AttributeInterface;
    public function setFillNameAsValue(bool $fillNameAsValue): AttributeInterface;
    public function setMultiple(bool $multiple, string $separator = ' '): AttributeInterface;
    public function setSeparator(string $separator): AttributeInterface;
    public function getName(): string;
    public function getValues(): array;
    public function getValueString(): string;
    /**
     * @param string[] $values
     * @return void
     */
    public function set(array $values): void;
    public function clear(): void;
    public function has(string $value): bool;

    /**
     * @param Closure|scalar|null $value
     * @return AttributeInterface
     */
    public function add($value): AttributeInterface;
    public function remove(string $value): bool;
    public function __toString(): string;
}
