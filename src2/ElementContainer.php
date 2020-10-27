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
  
        if($element->needParent()){
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

    public function render()
    {
        $output = '';
        foreach ($this->elements as $element) {
            $output .= $element->render();
        }
        return $output;
    }
}
