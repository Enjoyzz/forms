<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\Html\TypesRender;

class Html extends Input
{
    public function render(): string
    {
        return "<div{$this->getElement()->getAttributesString()}>{$this->getElement()->getLabel()}</div>";
    }
}
