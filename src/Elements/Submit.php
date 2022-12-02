<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\AttributeFactory;
use Enjoys\Forms\Element;

class Submit extends Element
{
    protected string $type = 'submit';

    public function __construct(string $name = null, string $title = null)
    {
        if (is_null($name)) {
            $name = uniqid('submit');
        }
        parent::__construct($name, $title);
        if (!is_null($title)) {
            $this->setAttribute(AttributeFactory::create('value', $title));
        }
    }
}
