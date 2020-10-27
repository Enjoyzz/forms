<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer;

/**
 * Description of RenderDescription
 *
 * @author Enjoys
 */
class RenderDescription
{

    private $element;
    private $containerTag;

    public function __construct($element, $containerTag = 'small')
    {
        $this->element = $element;
        $this->containerTag = $containerTag;
        
    }

    public function __toString()
    {
        if(!property_exists($this->element, 'getDescription')){
            return '';
        }
       
        if (empty($this->element->getDescription())) {
            return '';
        }
        return "<{$this->containerTag}{$this->element->getAttributes(\Enjoys\Forms2\Form::ATTRIBUTES_DESC)}>{$this->element->getDescription()}</{$this->containerTag}>";
    }
}
