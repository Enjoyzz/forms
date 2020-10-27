<?php

namespace Enjoys\Forms2\Traits;

/**
 *
 * @author Enjoys
 */
trait Container
{

    protected $elements = [];

    public function add(\Enjoys\Forms2\Element $element)
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
    public function remove(\Enjoys\Forms2\Element $element)
    {
        
    }
    
    /**
     * 
     * @method \Enjoys\Forms2\Elements\Text text($name, $label = null)
     * @method \Enjoys\Forms2\Elements\File file($name, $label = null)
     * 
     * @param string $name
     * @param array $arguments
     * @return @method
     * @throws Exception\ExceptionElement
     */
    public function __call(string $name, array $arguments)
    {
        $class_name = '\Enjoys\\Forms2\Elements\\' . ucfirst($name);
        if (!class_exists($class_name)) {
            throw new Exception\ExceptionElement("Class <b>{$class_name}</b> not found");
        }
        /** @var Element $element */
        $element = new $class_name(...$arguments);
        return $this->add($element);
        //return $element;
    }    

    public function getElements()
    {
        return $this->elements;
    }
}
