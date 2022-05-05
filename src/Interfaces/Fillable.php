<?php

declare(strict_types=1);

namespace Enjoys\Forms\Interfaces;

/**
 * Interface FillableInterface
 * @package Enjoys\Forms
 */
interface Fillable
{
    public function setParentName(string $parentName): void;

    public function getParentName(): string;

    public function fill(array $data): Fillable;

    public function getElements(): array;

    /**
     * @return mixed
     */
    public function getDefaultValue();
}
