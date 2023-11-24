<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

use Enjoys\Forms\Element;

/**
 * Interface FillableInterface
 * @package Enjoys\Forms
 */
interface Fillable
{
    public function setParentName(string $parentName): void;

    public function getParentName(): string;

    public function fill(array|\Closure $data, bool $useTitleAsValue = false): static;

    /**
     * @psalm-return array<array-key, Element&Fillable>
     */
    public function getElements(): array;

    public function getDefaultValue(): mixed;
}
