<?php

declare(strict_types=1);

namespace Enjoys\Forms\Renderer\ElementsRender;

/**
 * Class HtmlRender
 * @package Enjoys\Forms\Renderer\ElementsRender
 */
class HtmlRender extends BaseElement
{

    /**
     * @return string
     */
    public function render()
    {
        return "<div{$this->element->getAttributesString()}>{$this->element->getLabel()}</div>";
    }
}
