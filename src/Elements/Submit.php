<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;

class Submit extends Element
{
    protected string $type = 'submit';

    public function __construct(string $name, string $title = null)
    {
        parent::__construct($name, $title);
        if (!is_null($title)) {
            $this->setAttribute(AttributeFactory::create('value', $title));
        }
    }
}
