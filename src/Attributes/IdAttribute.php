<?php

declare(strict_types=1);

namespace Enjoys\Forms\Attributes;

use Enjoys\Forms\Attribute;

final class IdAttribute extends Attribute
{
    protected string $name = 'id';

    public function __construct()
    {
        $this->setWithoutValue(false);
    }
}
