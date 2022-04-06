<?php

declare(strict_types=1);


namespace Enjoys\Forms\Attributes;


use Enjoys\Forms\Attribute;

final class Action extends Attribute
{
    protected string $name = 'action';

    public function __construct()
    {
        $this->setFillNameAsValue(false);
        $this->setWithoutValue(false);
    }
}