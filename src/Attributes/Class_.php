<?php

declare(strict_types=1);

namespace Enjoys\Forms\Attributes;

use Enjoys\Forms\Attribute;

final class Class_ extends Attribute
{
    protected string $name = 'class';

    public function __construct()
    {
        $this->setMultiple(true);
        $this->setWithoutValue(false);
    }
}
