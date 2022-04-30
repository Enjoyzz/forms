<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;
use Enjoys\Forms\Interfaces\Ruleable;
use Enjoys\Forms\Traits\Rules;

class Hidden extends Element implements Ruleable
{
    use Rules;

    protected string $type = 'hidden';

    public function __construct(string $name, ?string $value = null)
    {
        parent::__construct($name);
        $this->setAttribute(AttributeFactory::create('value', $value)->setWithoutValue(false))
            ->removeAttribute('id');
    }
}
