<?php

declare(strict_types=1);


namespace Enjoys\Forms\Renderer\Html\TypesRender;


class Button extends Input
{
    public function render(): string
    {
        return $this->bodyRender($this->getElement());
    }
}