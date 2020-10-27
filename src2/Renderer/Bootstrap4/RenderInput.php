<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer\Bootstrap4;

/**
 * Description of RenderInput
 *
 * @author Enjoys
 */
class RenderInput
{
    private $element;
    
    public function __construct(\Enjoys\Forms2\Element $element)
    {
        $this->element = $element;
    }
    
    public function __toString()
    {
        $this->element->addClass('form-control');
        return $this->element->baseHtml();
    }
}
