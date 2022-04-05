<?php

declare(strict_types=1);


namespace Enjoys\Forms;


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
    public function set(array $values): void;
    public function clear(): void;
    public function has(mixed $value): bool;
    public function add(mixed $value): AttributeInterface;
    public function remove(string $value): bool;
}