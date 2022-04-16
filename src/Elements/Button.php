<?php

declare(strict_types=1);

namespace Enjoys\Forms\Elements;

use Enjoys\Forms\Element;

class Button extends Element
{
    public function baseHtml(): string
    {
        return "<button{$this->getAttributesString()}>{$this->getLabel()}</button>";
    }
}
