<?php

declare(strict_types=1);

namespace Enjoys\Forms2;

/**
 * Description of Composite
 *
 * @author Enjoys
 */
abstract class ElementContainer extends Element
{

    protected $elements = [];

    public function add(Element $element)
    {

        if ($element->needParent()) {
            $element->setParent($this);
            $element->prepare();
        }

        $this->elements[$element->getName()] = $element;
        return $element;
    }

    /**
     * 
     * @todo доделать
     */
    public function remove(Element $element)
    {
        
    }

    public function setDefaults($data)
    {
        foreach ($this->elements as $name => $element) {
            if (isset($data[$name])) {
                $element->setDefaults($data[$name]);
            }
        }
    }

    public function isComposite()
    {
        return true;
    }
    
    public function getElements()
    {
        return $this->elements;
    }

    public function render(\Enjoys\Forms2\Renderer\RendererInterface $renderer)
    {
        $output = '';
        foreach ($this->elements as $element) {
            $output .= $renderer->render($element);
        }
        return $output;
    }
}
