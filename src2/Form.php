<?php

declare(strict_types=1);

namespace Enjoys\Forms2;

/**
 * Description of Form
 *
 * @author Enjoys
 */
class Form extends ElementContainer
{
    use \Enjoys\Traits\Options;

    public function __construct(array $options = [])
    {
        $this->setOptions($options);
        $name = $this->name ?? \uniqid('form');
        parent::__construct($name, null);
        
        
        $this->hidden('token', 'toooodfjhashsahdvsd');
    }

    public function render()
    {
       // $output = parent::render();
        return "<form{$this->getAttributes()}>{$output}</form>";
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
    
    private function setMethod(string $method)
    {
        $this->setAttribute('method', $method);
    }
}
