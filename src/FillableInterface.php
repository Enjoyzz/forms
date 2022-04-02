<?php

declare(strict_types=1);

namespace Enjoys\Forms;

/**
 * Interface FillableInterface
 * @package Enjoys\Forms
 */
interface FillableInterface
{

    public function setParentName(string $parentName): void;

    public function getParentName(): string;

    public function fill(array $data);

    public function getElements(): array;

    /**
     * @return mixed
     */
    public function getDefaultValue();
}
