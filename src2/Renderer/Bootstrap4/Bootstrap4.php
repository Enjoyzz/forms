<?php

declare(strict_types=1);

namespace Enjoys\Forms2\Renderer\Bootstrap4;

/**
 * Description of Bootstrap4
 *
 * @author Enjoys
 */
class Bootstrap4 implements \Enjoys\Forms2\Renderer\RendererInterface
{
    
    private $element;

    public function __construct(\Enjoys\Forms2\Element $element, array $options)
    {
        $this->element = $element;
    }

    public function __toString(): string
    {
        if($this->element->isComposite()){
            
        }
        return $this->element->render();
    }
}
